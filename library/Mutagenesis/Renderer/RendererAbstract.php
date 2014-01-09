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

namespace Mutagenesis\Renderer;

abstract class RendererAbstract implements RendererInterface
{
    /**
     * @var bool
     */
    protected $detailCaptures = false;

    /**
     * @var string
     */
    protected $logPath = '';

    /**
     * Set flag to add detailed reports (including test results) about
     * the mutations which caused test failures (i.e. captured)
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setDetailCaptures($bool)
    {
        $this->detailCaptures = (bool) $bool;

        return $this;
    }

    /**
     * Get flag to add detailed reports (including test results) about
     * the mutations which caused test failures (i.e. captured)
     *
     * @return bool
     */
    public function isDetailCaptures()
    {
        return $this->detailCaptures;
    }

    /**
     * @param string $logPath
     *
     * @return RendererAbstract
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->logPath;
    }

    /**
     * Render the opening message (i.e. app and version mostly)
     *
     * @return string
     */
    public function renderOpening()
    {
        $out = 'Mutagenesis: Mutation Testing for PHP'
            . PHP_EOL . PHP_EOL;

        return $out;
    }

    /**
     * Render Mutagenesis output based on test pass. This is the pretest output,
     * rendered after a first-pass test run to ensure the test suite is in an
     * initial passing state.
     *
     * @param string $result Result state from test adapter
     * @param string $output Result output from test adapter
     *
     * @return string Pretest output to echo to client
     */
    public function renderPretest($result, $output)
    {
        if (!$result) {
            $out = 'Before you face the Mutants, you first need a 100% pass rate!'
                . PHP_EOL
                . 'That means no failures or errors (we\'ll allow skipped or incomplete tests).'
                . PHP_EOL . PHP_EOL
                . $output
                . PHP_EOL . PHP_EOL;

            return $out;
        }
        $out = 'All initial checks successful! The mutagenic slime has been activated.'
            . PHP_EOL . PHP_EOL
            . $this->indentTestOutput($output)
            . PHP_EOL . PHP_EOL
            . 'Stand by...Mutation Testing commencing.'
            . PHP_EOL . PHP_EOL;

        return $out;
    }

    /**
     * Render a progress marker indicating the execution of a single mutation
     * and the successful execution of the related test suite
     *
     * @param bool $result Whether unit tests passed (bad) or not (good)
     *
     * @return string
     */
    public function renderProgressMark($result)
    {
        if ($result === 'timed out') {
            return 'T';
        } elseif ($result === 'process failure') {
            return 'F';
        } elseif ($result) {
            return 'E';
        } else {
            return '.';
        }
    }

    /**
     * Utility function to prefix test output lines with an indent and equals sign
     *
     * @var string $output
     * @return string
     */
    protected function indentTestOutput($output)
    {
        $lines = explode("\n", $output);
        $out   = array();
        foreach ($lines as $line) {
            $out[] = '    > ' . $line;
        }
        $return = implode("\n", $out);

        return $return;
    }

    /**
     * @param int $total   Total mutations made and tested
     * @param int $escaped Number of mutations that did not cause a test failure
     *
     * @return float
     */
    public function calculateScore($total, $escaped)
    {
        if ($total === 0) {
            return 0;
        }

        return 100 - round($escaped / $total * 100, 2);
    }

}