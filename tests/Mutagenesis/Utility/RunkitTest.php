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

use Mutagenesis\Utility\Runkit;
use Mutagenesis\Mutation\OperatorAddition;
use Mutagenesis\Mutation\MutationAbstract;
use Mutagenesis\Testee\ClassMethod as Testee;
use Mutagenesis\Mutant\Mutant;

class RunkitTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->root = __DIR__ . '/_files';
    }

    public function testShouldApplyGivenMutationsUsingRunkitToReplaceEffectedMethods()
    {
        $mutationInstance = new OperatorAddition(2);

        $testeeInstance = new Testee();
        $testeeInstance->setFileName($this->root . '/runkit/Math1.php')
            ->setClassName('RunkitTest_Math1')
            ->setMethodName('add')
            ->setArguments('$op1,$op2')
            ->setTokens(array(array(335, 'return', 7), array(309, '$op1', 7), '+', array(309, '$op2', 7), ';'));

        $mutant = new Mutant($testeeInstance, $mutationInstance);

        $runkit = new Runkit();
        $runkit->applyMutation($mutant);
        $math = new \RunkitTest_Math1;
        $this->assertEquals(0, $math->add(1, 1));
        $runkit->reverseMutation($mutant);
    }

    public function testShouldRevertToOriginalMethodBodyWhenRequested()
    {
        $mutationInstance = new OperatorAddition(2);

        $testeeInstance = new Testee();
        $testeeInstance->setFileName($this->root . '/runkit/Math1.php')
            ->setClassName('RunkitTest_Math1')
            ->setMethodName('add')
            ->setArguments('$op1,$op2')
            ->setTokens(array(array(335, 'return', 7), array(309, '$op1', 7), '+', array(309, '$op2', 7), ';'));

        $mutant = new Mutant($testeeInstance, $mutationInstance);

        $runkit = new Runkit();
        $runkit->applyMutation($mutant);
        $math = new \RunkitTest_Math1;
        $runkit->reverseMutation($mutant);
        $this->assertEquals(2, $math->add(1, 1));
    }

    public function testShouldApplyGivenMutationsUsingRunkitToReplaceEffectedStaticMethods()
    {
        $mutationInstance = new OperatorAddition(2);

        $testeeInstance = new Testee();
        $testeeInstance->setFileName($this->root . '/runkit/Math2.php')
            ->setClassName('RunkitTest_Math2')
            ->setMethodName('add')
            ->setArguments('$op1,$op2')
            ->setTokens(array(array(335, 'return', 7), array(309, '$op1', 7), '+', array(309, '$op2', 7), ';'));

        $mutant = new Mutant($testeeInstance, $mutationInstance);

        $runkit = new Runkit();
        $runkit->applyMutation($mutant);
        $this->assertEquals(0, \RunkitTest_Math2::add(1, 1));
        $runkit->reverseMutation($mutant);
    }

    public function testShouldRevertToOriginalStaticMethodBodyWhenRequested()
    {
        $mutationInstance = new OperatorAddition(2);

        $testeeInstance = new Testee();
        $testeeInstance->setFileName($this->root . '/runkit/Math2.php')
            ->setClassName('RunkitTest_Math2')
            ->setMethodName('add')
            ->setArguments('$op1,$op2')
            ->setTokens(array(array(335, 'return', 7), array(309, '$op1', 7), '+', array(309, '$op2', 7), ';'));

        $mutant = new Mutant($testeeInstance, $mutationInstance);

        $runkit = new Runkit();
        $runkit->applyMutation($mutant);
        $runkit->reverseMutation($mutant);
        $this->assertEquals(2, \RunkitTest_Math2::add(1, 1));
    }
}

class StubMutagenesisMutation1 extends MutationAbstract
{
    public function getMutation(array $tokens, $index)
    {
    }
}
