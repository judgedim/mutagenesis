<?php
/**
 * Mutagenesis
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mutateme/blob/rewrite/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mutagenesis
 * @package    Mutagenesis
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mutateme/blob/rewrite/LICENSE New BSD License
 */

namespace MutagenesisTest;

use Mutagenesis\Mutation\LogicalIf;

class LogicalIfTest extends \PHPUnit_Framework_TestCase
{

    public function mutationProvider()
    {
        return array(
            array(
                "<?php if ((\$dave)) { echo 'awesome'; }",
                1,
                "<?php if (!((\$dave))) { echo 'awesome'; }",
                "Simple condition is negated"
            ),
            array(
                "<?php if (((\$dave)) { echo 'fail'; } ",
                1,
                "<?php if (((\$dave)) { echo 'fail'; } ",
                "If the parenthesis don't match, return original",
            ),
            array(
                "<?php if (\$dave == true) { echo 'dave'; }",
                1,
                "<?php if (\$dave == true) { echo 'dave'; }",
                "Defer to a more specific BooleanTrue mutation",
            ),
            array(
                "<?php if (\$dave == false) { echo 'dave'; }",
                1,
                "<?php if (\$dave == false) { echo 'dave'; }",
                "Defer to a more specific BooleanFalse mutation",
            ),
            array(
                "<?php if ((\$dave + 2) == 4) { echo 'dave'; }",
                1,
                "<?php if ((\$dave + 2) == 4) { echo 'dave'; }",
                "Defer to a more specific OperatorAddition mutation",
            ),
            array(
                "<?php if ((\$dave - 2) == 4) { echo 'dave'; }",
                1,
                "<?php if ((\$dave - 2) == 4) { echo 'dave'; }",
                "Defer to a more specific OperatorSubtraction mutation",
            ),
            array(
                "<?php if ((\$dave && \$bob) { echo 'dave and bob'; }",
                1,
                "<?php if ((\$dave && \$bob) { echo 'dave and bob'; }",
                "Defer to a more specific BooleanAnd mutation",
            ),
            array(
                "<?php if ((\$dave || \$bob) { echo 'dave and bob'; }",
                1,
                "<?php if ((\$dave || \$bob) { echo 'dave and bob'; }",
                "Defer to a more specific BooleanOr mutation",
            ),
        );
    }

    /**
     * @dataProvider mutationProvider
     */
    public function testReturnsTokenEquivalentToWrappedConditional($code, $index, $expected)
    {
        $mutation = new LogicalIf($index);
        $tokens = token_get_all($code);
        $this->assertEquals(
            $expected,
            $mutation->mutate($tokens, $index)
        );
    }
}
