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

use Mockery as m;

class Mutagenesis_Adapter_AdapterAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Mutagenesis\Adapter\AdapterAbstract
     */
    protected $adapter;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->adapter = m::mock('\Mutagenesis\Adapter\AdapterAbstract[runTests]');
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }

    public function testSetOutput()
    {
        $this->assertNull($this->adapter->setOutput('test'));
    }

    public function testGetOutputDefaultState()
    {
        $this->assertEquals('', $this->adapter->getOutput());
    }

    public function testGetOutputAfterChange()
    {
        $expectedResult = sha1(microtime(true));
        $this->adapter->setOutput($expectedResult);

        $this->assertEquals($expectedResult, $this->adapter->getOutput());
    }
}