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

namespace Mutagenesis\Adapter;

use Mutagenesis\Runner\Base as BaseRunner;
use Mutagenesis\Mutant\MutantInterface;

abstract class AdapterAbstract
{

    const TIMED_OUT = 'timed out';

    const PROCESS_FAILURE = 'process failure';

    /**
     * Output from the test library in use
     *
     * @var string
     */
    protected $_output = '';

    /**
     * Runs the tests suite according to Runner set options and the execution
     * order of test case (if any). It then returns an array of two elements.
     * First element is a boolean result value indicating if tests passed or not.
     * Second element is an array containing the key "stdout" which stores the
     * output from the last test run.
     *
     * @abstract
     *
     * @param \Mutagenesis\Runner\Base $runner
     * @param bool                     $useStdout
     * @param bool                     $firstRun
     * @param MutantInterface|bool     $mutant
     * @param array                    $testCases
     *
     * @return array
     */
    abstract public function runTests(BaseRunner $runner, $useStdout = false,
                                      $firstRun = false, $mutant = false, array $testCases = array());

    /**
     * Set the test library output so it can be used later
     *
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->_output = $output;
    }

    /**
     * Get the test library output
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->_output;
    }

}
