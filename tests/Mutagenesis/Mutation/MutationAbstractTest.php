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

use Mutagenesis\Mutation\MutationAbstract;
use Mockery as m;

class MutationAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Mutagenesis\Mutation\MutationAbstract
     */
    public $mutation;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->mutation = m::mock('\Mutagenesis\Mutation\MutationAbstract[getMutation]');
    }

    public function testGetDiffProviderDefault()
    {
        $result = $this->mutation->getDiffProvider();

        $this->assertInstanceOf('\Mutagenesis\Utility\Diff\PhpUnit', $result);
    }

    public function testSetDiffProvider()
    {
        /**
         * @var \Mutagenesis\Utility\Diff\ProviderInterface $provider
         */
        $provider = m::mock('\Mutagenesis\Utility\Diff\ProviderInterface');

        $result = $this->mutation->setDiffProvider($provider);

        $this->assertSame($this->mutation, $result);
    }

    public function testSetDiffProviderValidate()
    {
        /**
         * @var \Mutagenesis\Utility\Diff\ProviderInterface $provider
         */
        $provider = m::mock('\Mutagenesis\Utility\Diff\ProviderInterface');

        $this->mutation->setDiffProvider($provider);
        $result = $this->mutation->getDiffProvider();

        $this->assertSame($provider, $result);
    }
}