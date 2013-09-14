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

use Mutagenesis\Generator;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->root = __DIR__ . '/_files/root/base1';
        $this->badRoot = '/path/does/not/exist';
    }

    public function testShouldStoreSourceDirectoryValue()
    {
        $generator = new Generator();
        $generator->setSourceDirectory($this->root . '/library');
        $this->assertEquals($this->root . '/library', $generator->getSourceDirectory());
    }

    /**
     * @expectedException \Mutagenesis\FUTException
     */
    public function testShouldThrowExceptionOnNonexistingDirectory()
    {
        $generator = new Generator();
        $generator->setSourceDirectory($this->badRoot);
    }

    public function testShouldCollateAllFilesValidForMutationTesting()
    {
        $generator = new Generator();
        $generator->setSourceDirectory($this->root);
        $this->assertEquals(array(
            $this->root . '/library/bool2.php',
            $this->root . '/library/bool1.php'
        ),$generator->getFiles());
    }

    public function testShouldGenerateMutableFileObjects()
    {
        $generator = new Generator();
        $generator->setSourceDirectory($this->root);
        $mutable = $this->getMock('\Mutagenesis\Mutable', array('generate', 'setFilename'));
        $generator->generate($mutable);
        $mutables = $generator->getMutables();
        $this->assertInstanceOf('\Mutagenesis\Mutable', $mutables[0]);
    }

    public function testShouldGenerateAMutableFileObjectPerDetectedFile()
    {
        $generator = new Generator();
        $generator->setSourceDirectory($this->root);
        $mutable = $this->getMock('\Mutagenesis\Mutable', array('generate', 'setFilename'));
        $generator->generate($mutable);
        $this->assertEquals(2, count($generator->getMutables()));
    }

}
