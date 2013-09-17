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

use Mutagenesis\Mutable;

class MutableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $root = '';

    /**
     * @return void
     */
    public function setUp()
    {
        $this->root = __DIR__ . '/_files/root/base2/library';
    }

    /**
     * @test
     */
    public function shouldMaintainFilePathInfoOncePassedInConstructor()
    {
        $file = new Mutable($this->root . '/foo.php');
        $this->assertEquals($this->root . '/foo.php', $file->getFilename());
    }

    /**
     * @test
     */
    public function shouldNotHaveMutationsBeforeGeneration()
    {
        $file = new Mutable($this->root . '/math1.php');
        $this->assertEquals(array(), $file->getMutations());
    }

    /**
     * @test
     */
    public function shouldNotHaveDetectedMutablesBeforeGeneration()
    {
        $file = new Mutable($this->root . '/math1.php');
        $this->assertEquals(array(), $file->getMutables());
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutablesForEmptyClass()
    {
        $file = new Mutable($this->root . '/math0.php');
        $file->generate();
        $this->assertEquals(array(), $file->getMutables());
    }

    /**
     * @test
     */
    public function shouldNotgenerateForEmptyClass()
    {
        $file = new Mutable($this->root . '/math0.php');
        $file->generate();
        $this->assertEquals(array(), $file->getMutations());
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutationsIfOnlyEmptyMethodsInClass()
    {
        $file = new Mutable($this->root . '/math00.php');
        $file->generate();
        $this->assertEquals(array(), $file->getMutations());
    }

    /**
     * @test
     */
    public function shouldGenerateMutablesEvenIfMethodBodyIsNotViable()
    {
        $file = new Mutable($this->root . '/math000.php');
        $file->generate();
        $return = $file->getMutables();
        $this->assertEquals(array('file', 'class', 'method', 'args', 'tokens'), array_keys($return[0]));
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutablesIfMethodBodyIsNotViable()
    {
        $file = new Mutable($this->root . '/math000.php');
        $file->generate();
        $this->assertEquals(array(), $file->getMutations());
    }

    /**
     * @test
     */
    public function shouldGenerateAMutationIfPossible()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(array('file', 'class', 'method', 'args', 'tokens', 'index', 'mutation'), array_keys($return[0]));
    }

    /**
     * @test
     */
    public function shouldReturnMutationsAsMutantObjectWrappers()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertInstanceOf('\Mutagenesis\Mutation\MutationAbstract', $return[0]['mutation']);
    }

    /**
     * @test
     */
    public function shouldDetectMutablesForClassesInSameFileSeparately()
    {
        $file = new Mutable($this->root . '/mathx2.php');
        $file->generate();
        $return = $file->getMutables();
        $this->assertEquals('Math2', $return[1]['class']);
    }

    /**
     * @test
     */
    public function shouldDetectMutationsForClassesInSameFileSeparately()
    {
        $file = new Mutable($this->root . '/mathx2.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals('Math2', $return[1]['class']);
    }


    // Ensure correct class is returned as a mutation

    /**
     * @test
     */
    public function shouldGenerateAdditionOperatorMutationWhenPlusSignDetected()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorAddition', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('OperatorAddition'));
        $this->assertFalse($file->hasMutation('OperatorSubtraction'));
    }

    /**
     * @test
     */
    public function shouldGenerateSubtractionOperatorMutationWhenMinusSignDetected()
    {
        $file = new Mutable($this->root . '/math2.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorSubtraction', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('OperatorSubtraction'));
        $this->assertFalse($file->hasMutation('OperatorAddition'));
    }

    /**
     * @test
     */
    public function shouldGenerateIncrementOperatorMutationWhenPostIncrementDetected()
    {
        $file = new Mutable($this->root . '/math3.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorIncrement', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('OperatorIncrement'));
        $this->assertFalse($file->hasMutation('OperatorAddition'));
    }

    /**
     * @test
     */
    public function shouldGenerateIncrementOperatorMutationWhenPreIncrementDetected()
    {
        $file = new Mutable($this->root . '/math4.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorIncrement', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('OperatorIncrement'));
        $this->assertFalse($file->hasMutation('OperatorAddition'));
    }

    /**
     * @test
     */
    public function shouldGenerateBooleanTrueMutationWhenBoolTrueDetected()
    {
        $file = new Mutable($this->root . '/bool1.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\BooleanTrue', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('BooleanTrue'));
        $this->assertFalse($file->hasMutation('OperatorAddition'));
    }

    /**
     * @test
     */
    public function shouldNotThrowNoticeWhenParameterPassedByReferenceToAnonymousFunction()
    {
        $file = new Mutable($this->root . '/bool3.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(0, count($return));
    }

    /**
     * @test
     */
    public function shouldGenerateBooleanFalseMutationWhenBoolFalseDetected()
    {
        $file = new Mutable($this->root . '/bool2.php');
        $file->generate();
        $return = $file->getMutations();
        $this->assertEquals(1, count($return));
        $this->assertInstanceOf('\Mutagenesis\Mutation\BooleanFalse', $return[0]['mutation']);
        $this->assertTrue($file->hasMutation('BooleanFalse'));
        $this->assertFalse($file->hasMutation('OperatorAddition'));
    }

    /**
     * @test
     */
    public function cleanupShouldResetMutationsAndMutables()
    {
        $file = new Mutable($this->root . '/bool2.php');
        $file->generate();
        $mutations = $file->getMutations();
        $mutables = $file->getMutables();

        $this->assertGreaterThan(0, count($mutations));
        $this->assertGreaterThan(0, count($mutables));

        $file->cleanup();

        $this->assertEquals(array(), $file->getMutations());
        $this->assertEquals(array(), $file->getMutables());
    }

    /**
     * @test
     */
    public function cleanupShouldImplementsFluentInterface()
    {
        $file = new Mutable($this->root . '/bool2.php');
        $this->assertSame($file, $file->cleanup());
    }
    
    /**
     * Covers bug where Mutable may incorrectly parse a method and omit the first
     * opening bracket in an IF clause, leading to syntax errors when
     * attempting to add the new method block via runkit
     *
     * @group MM1
     */
    public function testCreatesAccurateMapOfIfClausesSingleNonStaticMethod()
    {
        $file = new Mutable(__DIR__ . '/_files/IfClause.php');
        $file->generate();
        $mutations = $file->getMutations();
        $mutation = $mutations[0];
        $this->assertEquals(__DIR__ . '/_files/IfClause.php', $mutation['file']);
        $this->assertEquals('Some_Class_With_If_Clause_In_Method', $mutation['class']);
        $this->assertEquals('_getSession', $mutation['method']);
        $this->assertEquals('', $mutation['args']);
        $block = <<<BLOCK

        static \$session = null;
        if (\$session === null) {
            \$session = new Zend_Session_Namespace(
                \$this->getSessionNamespace(), true
            );
        }
    
BLOCK;
        $this->assertEquals($block, $this->_reconstructFromTokens($mutation['tokens']));
    }
    
    /**
     * Reconstruct a string of source code from its constituent tokens
     *
     * @param array $tokens
     * @return string
     */
    protected function _reconstructFromTokens(array $tokens)
    {
        $str = '';
        foreach ($tokens as $token) {
            if (is_string($token)) {
                $str .= $token;
            } else {
                $str .= $token[1];
            }
        }
        return $str;
    }

}
