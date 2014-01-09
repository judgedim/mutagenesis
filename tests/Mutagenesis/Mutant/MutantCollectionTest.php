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

use Mutagenesis\Mutant\MutantCollection;

class MutantCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MutantCollection
     */
    private $mutantCollection;

    public function setUp()
    {
        $this->mutantCollection = new MutantCollection();
    }

    public function testGetAll()
    {
        $this->assertInstanceOf('SplObjectStorage', $this->mutantCollection->all());
    }

    public function testCountAndPush()
    {
        $mutant = $this->getMutantMock();
        $this->mutantCollection->push($mutant);

        $this->assertEquals(1, $this->mutantCollection->count());
    }

    public function testGetMutantsCaptured()
    {
        $mutant = $this->getMutantMock();
        $mutant->expects($this->any())
            ->method('isCaptured')
            ->will($this->returnValue(true));

        $this->mutantCollection->push($mutant);

        $this->assertEquals(1, count($this->mutantCollection->getMutantsCaptured()));
    }

    public function testGetMutantsEscaped()
    {
        $mutant = $this->getMutantMock();
        $mutant->expects($this->any())
            ->method('isCaptured')
            ->will($this->returnValue(false));

        $this->mutantCollection->push($mutant);

        $this->assertEquals(1, count($this->mutantCollection->getMutantsEscaped()));
    }

    /**
     * @return mixed
     */
    private function getMutantMock()
    {
        $mutant = $this->getMock(
            'Mutagenesis\\Mutant\\Mutant',
            array(
                'isCaptured'
            ),
            array(),
            '',
            false
        );

        return $mutant;
    }
}
 