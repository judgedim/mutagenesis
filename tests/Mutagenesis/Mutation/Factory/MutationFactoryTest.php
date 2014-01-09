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

use Mutagenesis\Mutation\Factory\MutationFactory;
use Mutagenesis\Mutation;

class MutationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreateMutationEquivalentToExpected($token, $index, $expected)
    {
        $result = MutationFactory::create($token, $index);

        $this->assertEquals($expected, $result);
    }

    public function createProvider()
    {
        return array(
            array(
                '',
                1,
                false
            ),
            array(
                ' ',
                1,
                false
            ),
            array(
                '=',
                1,
                false
            ),
            array(
                '+',
                1,
                new Mutation\OperatorAddition(1)
            ),
            array(
                '-',
                1,
                new Mutation\OperatorSubtraction(1)
            ),
            array(
                '/',
                1,
                new Mutation\OperatorArithmeticDivision(1)
            ),
            array(
                array(''),
                1,
                false
            ),
            array(
                array(T_WHITESPACE, '\n  ', 1),
                1,
                false
            ),
            array(
                array(T_VARIABLE, '$a', 1),
                1,
                false
            ),
            array(
                array(),
                1,
                false
            ),
            array(
                array(T_INC, '++', 1),
                1,
                new Mutation\OperatorIncrement(1)
            ),
            array(
                array(T_DEC, '--', 1),
                1,
                new Mutation\OperatorDecrement(1)
            ),
            array(
                array(T_IS_NOT_EQUAL, '!=', 1),
                1,
                new Mutation\OperatorComparisonNotEqual(1)
            ),
            array(
                array(T_BOOLEAN_AND, '&&', 1),
                1,
                new Mutation\BooleanAnd(1)
            ),
            array(
                array(T_BOOLEAN_OR, '||', 1),
                1,
                new Mutation\BooleanOr(1)
            ),
            array(
                array(T_CONSTANT_ENCAPSED_STRING, '"foo"', 1),
                1,
                new Mutation\ScalarString(1)
            ),
            array(
                array(T_LNUMBER, '123', 1),
                1,
                new Mutation\ScalarInteger(1)
            ),
            array(
                array(T_IF, 'if', 1),
                1,
                new Mutation\LogicalIf(1)
            ),
            array(
                array(T_ELSEIF, 'elseif', 1),
                1,
                new Mutation\LogicalIf(1)
            ),
            array(
                array(T_STRING, 'some', 1),
                1,
                false
            ),
            array(
                array(T_STRING, 'true', 1),
                1,
                new Mutation\BooleanTrue(1)
            ),
            array(
                array(T_STRING, 'false', 1),
                1,
                new Mutation\BooleanFalse(1)
            ),
        );
    }
}
 