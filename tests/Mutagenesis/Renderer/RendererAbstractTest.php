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

use Mutagenesis\Renderer\RendererAbstract;

class RendererAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RendererAbstract
     */
    private $_renderer;

    public function setUp()
    {
        $this->_renderer = $this->getMockForAbstractClass('Mutagenesis\\Renderer\\RendererAbstract');
    }

    public function testRendersOpeningMessage()
    {
        $this->assertEquals(
            'Mutagenesis: Mutation Testing for PHP' . PHP_EOL . PHP_EOL,
            $this->_renderer->renderOpening()
        );
    }

    public function testRendersFailMessageIfTestSuiteDidNotPassDuringPretest()
    {
        $result     = false;
        $testOutput = 'Stuff failed';
        $this->assertEquals(
            'Before you face the Mutants, you first need a 100% pass rate!'
            . PHP_EOL
            . 'That means no failures or errors (we\'ll allow skipped or incomplete tests).'
            . PHP_EOL . PHP_EOL
            . $testOutput
            . PHP_EOL . PHP_EOL,
            $this->_renderer->renderPretest($result, $testOutput)
        );
    }

    public function testRendersPassMessageIfTestSuiteDidPassDuringPretest()
    {
        $result     = true;
        $testOutput = 'Stuff passed';
        $this->assertEquals(
            'All initial checks successful! The mutagenic slime has been activated.'
            . PHP_EOL . PHP_EOL
            . '    > ' . $testOutput
            . PHP_EOL . PHP_EOL . 'Stand by...Mutation Testing commencing.'
            . PHP_EOL . PHP_EOL,
            $this->_renderer->renderPretest($result, $testOutput)
        );
    }

    public function testRendersProgressMarkAsPeriodCharacterIfTestResultWasFalse()
    {
        $this->assertEquals('.', $this->_renderer->renderProgressMark(false));
    }

    public function testRendersProgressMarkAsECharacterIfTestResultWasFalse()
    {
        $this->assertEquals('E', $this->_renderer->renderProgressMark(true));
    }

    /**
     * @dataProvider calculateScoreProvider
     */
    public function testCalculateScore($total, $escaped, $expected)
    {
        $result = $this->_renderer->calculateScore($total, $escaped);

        $this->assertEquals($expected, $result);
    }

    public function calculateScoreProvider()
    {
        return array(
            array(
                0,
                0,
                0
            ),
            array(
                0,
                10,
                0
            ),
            array(
                10,
                5,
                50
            )
        );
    }

}
 