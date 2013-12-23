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

use Mutagenesis\Runner\Base;

class RunnerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->root = dirname(__DIR__) . '/_files/root/base1';
        $this->badRoot = '/path/does/not/exist';
    }

    public function testShouldStoreSourceDirectoryValue()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $this->assertEquals($this->root, $runner->getSourceDirectory());
    }

    /**
     * @expectedException \Mutagenesis\Runner\Exception\RuntimeException
     */
    public function testShouldThrowExceptionOnNonexistingDirectoryWhenSettingSourceDirectory()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->badRoot);
    }

    public function testShouldStoreSourceExcludeValue()
    {
        $runner = new \Mutagenesis\Runner\Base;
        $runner->setSourceExcludes($exc = array("123.php", "456.php"));
        $this->assertEquals($exc, $runner->getSourceExcludes());
    }

    public function testShouldStoreTestDirectoryValue()
    {
        $runner = new Base();
        $runner->setTestDirectory($this->root);
        $this->assertEquals($this->root, $runner->getTestDirectory());
    }

    /**
     * @expectedException \Mutagenesis\Runner\Exception\RuntimeException
     */
    public function testShouldThrowExceptionOnNonexistingDirectoryWhenSettingTestDirectory()
    {
        $runner = new Base();
        $runner->setTestDirectory($this->badRoot);
    }

    public function testShouldStoreAdapterNameValue()
    {
        $runner = new Base();
        $runner->setAdapterName('PHPSpec');
        $this->assertEquals('PHPSpec', $runner->getAdapterName());
    }
    
    public function testShouldStoreRendererNameValue()
    {
        $runner = new Base();
        $runner->setRendererName('Html');
        $this->assertEquals('Html', $runner->getRendererName());
    }

    public function testShouldStoreGeneratorObjectIfProvided()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $generator = $this->getMock('\Mutagenesis\Generator');
        $runner->setGenerator($generator);
        $this->assertSame($generator, $runner->getGenerator());
    }

    public function testShouldCreateGeneratorWhenNeededIfNoneProvided()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $this->assertInstanceOf('\Mutagenesis\Generator', $runner->getGenerator());
    }

    public function testShouldSetGeneratorSourceDirectoryWhenGeneratorCreated()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $this->assertEquals($this->root, $runner->getGenerator()->getSourceDirectory());
    }

    public function testShouldSetGeneratorSourceDirectoryWhenGeneratorProvided()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $generator = $this->getMock('\Mutagenesis\Generator');
        $generator->expects($this->once())
            ->method('setSourceDirectory')
            ->with($this->equalTo($this->root));
        $runner->setGenerator($generator);
    }

    public function testShouldUseGeneratorToCreateMutablesAndStoreAllForRetrievalUsingGetMutablesMethod()
    {
        $runner = new Base();
        $generator = $this->getMock('\Mutagenesis\Generator');
        $generator->expects($this->once())
            ->method('generate');
        $generator->expects($this->once())
            ->method('getMutables')
            ->will($this->returnValue(array('mut1', 'mut2')));
        $runner->setGenerator($generator);
        $this->assertEquals(array('mut1', 'mut2'), $runner->getMutables());
    }

    public function testShouldGenerateMutablesWhenRequestedButNotYetAvailable()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $this->assertEquals(2, count($runner->getMutables()));
    }

    public function testShouldProvideTestingAdapterIfAlreadyAvailable()
    {
        $runner = new Base();
        $adapter = $this->getMockForAbstractClass('\Mutagenesis\Adapter\AdapterAbstract');
        $runner->setAdapter($adapter);
        $this->assertSame($adapter, $runner->getAdapter());
    }
    
    public function testShouldProvideRendererIfAlreadyAvailable()
    {
        $runner = new Base();
        $renderer = $this->getMock('\Mutagenesis\Renderer\RendererInterface');
        $runner->setRenderer($renderer);
        $this->assertSame($renderer, $runner->getRenderer());
    }

    public function testShouldCreateTestingAdapterIfNotAlreadyAvailable()
    {
        $runner = new Base();
        $runner->setAdapterName('PHPUNIT');
        $this->assertInstanceOf('\Mutagenesis\Adapter\Phpunit', $runner->getAdapter());
    }
    
    public function testShouldCreateDefaultTextRendererIfOtherInstanceOrNameNotAlreadyAvailable()
    {
        $runner = new Base();
        $this->assertInstanceOf('\Mutagenesis\Renderer\Text', $runner->getRenderer());
    }

    /**
     * @expectedException \Mutagenesis\Runner\Exception\ConfigurationException
     */
    public function testShouldThrowExceptionIfAdapterNameGivenIsNotSupported()
    {
        $runner = new Base();
        $runner->setAdapterName('DOESNOTCOMPUTE');
        $runner->getAdapter();
    }

    public function testShouldCreateRunkitWrapperIfNotAvailable()
    {
        $runner = new Base();
        $runner->setSourceDirectory($this->root);
        $this->assertInstanceOf('\Mutagenesis\Utility\Runkit', $runner->getRunkit());
    }

    public function testShouldStoreCacheDirectoryValue()
    {
        $runner = new Base();
        $runner->setCacheDirectory($this->root);
        $this->assertEquals($this->root, $runner->getCacheDirectory());
    }

    public function testCacheDirectoryDefaultsToTmpIfNotSet()
    {
        $runner = new Base();
        $this->assertEquals(sys_get_temp_dir(), $runner->getCacheDirectory());
    }

    public function testShouldStoreCliOptions()
    {
        $runner = new Base();
        $runner->setAdapterOption('foo')->setAdapterOption('bar');
        $this->assertEquals(array('foo', 'bar'), $runner->getAdapterOptions());
    }
}
