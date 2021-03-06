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

use Mutagenesis\Renderer\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Text
     */
    private $_renderer;

    public function setUp()
    {
        $this->_renderer = new Text();
    }

    public function testGetDiffProvider()
    {
        $this->assertInstanceOf('Mutagenesis\\Utility\\Diff\\PhpUnit', $this->_renderer->getDiffProvider());
    }

    public function testRendersFinalReportWithNoEscapeesFromASingleMutant()
    {
        $this->assertEquals(
            PHP_EOL . PHP_EOL
            . 'Score: 100%'
            . PHP_EOL . PHP_EOL
            . '1 Mutant born out of the mutagenic slime!'
            . PHP_EOL . PHP_EOL
            . 'No Mutants survived! Someone in QA will be happy.'
            . PHP_EOL . PHP_EOL,
            $this->_renderer->renderReport(1, 1, 0, array(), array(), '')
        );
    }

    public function testRendersFinalReportWithEscapeesFromASingleMutant()
    {
        $expected = <<<EXPECTED


Score: 0%

1 Mutant born out of the mutagenic slime!

1 Mutant escaped; the integrity of your source code may be compromised by the following Mutants:

1)
Difference on Foo::bar() in /path/to/foo.php
===================================================================
diff1
    > test1output

Happy Hunting! Remember that some Mutants may just be Ghosts (or if you want to be boring, 'false positives').


EXPECTED;
        $mutable  = $this->getMock(
            'Mutagenesis\\Mutable',
            array(
                'getMutantsEscaped',
                'getMutantsCaptured'
            )
        );

        $mutant = $this->getMock(
            'Mutagenesis\\Mutant\\Mutant',
            array(
                'getClassName',
                'getMethodName',
                'getFileName',
                'getDiff',
            ),
            array(),
            '',
            false
        );

        $mutant->expects($this->once())
            ->method('getClassName')
            ->will($this->returnValue('Foo'));

        $mutant->expects($this->once())
            ->method('getMethodName')
            ->will($this->returnValue('bar'));

        $mutant->expects($this->once())
            ->method('getFileName')
            ->will($this->returnValue('/path/to/foo.php'));

        $mutant->expects($this->once())
            ->method('getDiff')
            ->will($this->returnValue('diff1'));

        $mutable->expects($this->once())
            ->method('getMutantsEscaped')
            ->will($this->returnValue(array(
                $mutant
            )));


        $mutables = array(
            $mutable
        );

        $this->assertEquals(
            $expected,
            $this->_renderer->renderReport(1, 0, 1, $mutables, 'test1output')
        );
    }
}