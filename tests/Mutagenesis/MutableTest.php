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
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldNotHaveDetectedMutablesBeforeGeneration()
    {
        $file = new Mutable($this->root . '/math1.php');
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutablesForEmptyClass()
    {
        $file = new Mutable($this->root . '/math0.php');
        $file->generate();
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldNotgenerateForEmptyClass()
    {
        $file = new Mutable($this->root . '/math0.php');
        $file->generate();
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutationsIfOnlyEmptyMethodsInClass()
    {
        $file = new Mutable($this->root . '/math00.php');
        $file->generate();
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldGenerateMutablesEvenIfMethodBodyIsNotViable()
    {
        $file = new Mutable($this->root . '/math000.php');
        $file->generate();
        foreach ($file->getMutables() as $testee) {
            $this->assertInstanceOf('\Mutagenesis\Testee\TesteeInterface', $testee);
        }
    }

    /**
     * @test
     */
    public function shouldNotGenerateMutablesIfMethodBodyIsNotViable()
    {
        $file = new Mutable($this->root . '/math000.php');
        $file->generate();
        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldGenerateAMutationIfPossible()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        foreach ($file->getMutables() as $testee) {
            $this->assertInstanceOf('\Mutagenesis\Testee\TesteeInterface', $testee);
        }
    }

    /**
     * @test
     */
    public function shouldReturnMutationsAsMutantObjectWrappers()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        foreach ($file->getMutables() as $testee) {
            $this->assertInstanceOf('\Mutagenesis\Testee\TesteeInterface', $testee);
        }
    }

    /**
     * @test
     */
    public function shouldDetectMutablesForClassesInSameFileSeparately()
    {
        $file = new Mutable($this->root . '/mathx2.php');
        $file->generate();
        foreach ($file->getMutables() as $testee) {
            $this->assertTrue(in_array($testee->getClassName(), array('Math2', 'Math1')));
        }
    }

    /**
     * @test
     */
    public function shouldDetectMutationsForClassesInSameFileSeparately()
    {
        $file = new Mutable($this->root . '/mathx2.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutants->next();
        $mutant = $mutants->current();
        $this->assertEquals('Math2', $mutant->getClassName());
    }

    /**
     * @test
     */
    public function shouldDetectArgs()
    {
        $file = new Mutable($this->root . '/mathx2.php');
        $file->generate();
        foreach ($file->getMutables() as $testee) {
            $this->assertEquals('$op1, $op2', $testee->getArguments());
        }
    }

    // Ensure correct class is returned as a mutation

    /**
     * @test
     */
    public function shouldGenerateAdditionOperatorMutationWhenPlusSignDetected()
    {
        $file = new Mutable($this->root . '/math1.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorAddition', $mutant->getMutation());
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
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorSubtraction', $mutant->getMutation());
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
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorIncrement', $mutant->getMutation());
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
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\OperatorIncrement', $mutant->getMutation());
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
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\BooleanTrue', $mutant->getMutation());
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
        $this->assertEquals(0, $file->getMutants()->count());
    }

    /**
     * @test
     */
    public function shouldGenerateBooleanFalseMutationWhenBoolFalseDetected()
    {
        $file = new Mutable($this->root . '/bool2.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(1, count($mutants));
        $this->assertInstanceOf('\Mutagenesis\Mutation\BooleanFalse', $mutant->getMutation());
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
        $mutants = $file->getMutants();
        $mutables = $file->getMutables();

        $this->assertGreaterThan(0, count($mutants));
        $this->assertGreaterThan(0, count($mutables));

        $file->cleanup();

        $this->assertInstanceOf('\Mutagenesis\Mutant\MutantCollectionInterface', $file->getMutants());
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
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(__DIR__ . '/_files/IfClause.php', $mutant->getFileName());
        $this->assertEquals('Some_Class_With_If_Clause_In_Method', $mutant->getClassName());
        $this->assertEquals('_getSession', $mutant->getMethodName());
        $this->assertEquals('', $mutant->getArguments());
        $block = <<<BLOCK

        static \$session = null;
        if (\$session === null) {
            \$session = new Zend_Session_Namespace(
                \$this->getSessionNamespace(), true
            );
        }
    
BLOCK;
        $this->assertEquals($block, $this->_reconstructFromTokens($mutant->getTokens()));
    }

    public function testCreatesFullyNamespacedClassNames()
    {
        $file = new Mutable(dirname(__FILE__) . '/_files/SomeNamespacedClassName.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(dirname(__FILE__) . '/_files/SomeNamespacedClassName.php', $mutant->getFileName());
        $this->assertEquals('ClassName', $mutant->getClassName());
    }

    public function testCreatesAccurateMapOfBracesWithComplexStringInterning()
    {
        $file = new Mutable(dirname(__FILE__) . '/_files/ComplexInternString.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(dirname(__FILE__) . '/_files/ComplexInternString.php', $mutant->getFileName());
        $this->assertEquals('Some_Class_With_ComplexInternString', $mutant->getClassName());
        $this->assertEquals('_getSession', $mutant->getMethodName());
        $this->assertEquals('', $mutant->getArguments());
        $block = <<<BLOCK

        static \$session = null;
        if (\$session === null) {
            \$dave = "{\$session['dave']}";
            return true;
        }

        return false;
    
BLOCK;
        $this->assertEquals($block, $this->_reconstructFromTokens($mutant->getTokens()));
    }
    
    public function testCreatesLeavesClosuresIntact()
    {
        $file = new Mutable(dirname(__FILE__) . '/_files/Closure.php');
        $file->generate();
        $mutants = $file->getMutants()->all();
        $mutants->rewind();
        $mutant = $mutants->current();
        $this->assertEquals(dirname(__FILE__) . '/_files/Closure.php', $mutant->getFileName());
        $this->assertEquals('Some_Class_With_Closure', $mutant->getClassName());
        $this->assertEquals('setSession', $mutant->getMethodName());
        $this->assertEquals('$session = null', $mutant->getArguments());
        $block = <<<BLOCK

        if (\$session === null) {
            \$dave = function(Closure \$func, array \$d) use (\$session) {
                \$d = \$session;
            };
            return true;
        }

        return false;
    
BLOCK;
        $this->assertEquals($block, $this->_reconstructFromTokens($mutant->getTokens()));
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
