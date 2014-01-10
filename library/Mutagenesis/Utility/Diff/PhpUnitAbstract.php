<?php
/**
 * Mutagenesis
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mutagenesis/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mutagenesis
 * @package    Mutagenesis
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mutagenesis/blob/master/LICENSE New BSD License
 */

namespace Mutagenesis\Utility\Diff;

use SebastianBergmann\Diff\Differ;

abstract class PhpUnitAbstract implements ProviderInterface
{
    /**
     * Returns the diff between two arrays or strings as string.
     *
     * @param array|string $from
     * @param array|string $to
     * @param int          $contextLines
     *
     * @return string
     */
    public function difference($from, $to, $contextLines = 3)
    {
        $tool = new Differ('');
        $diff = $tool->diffToArray($from, $to);

        $inOld = false;
        $i     = 0;
        $old   = array();

        foreach ($diff as $line) {
            if ($line[1] === 0 /* OLD */) {
                if ($inOld === false) {
                    $inOld = $i;
                }
            } else if ($inOld !== false) {
                if (($i - $inOld) > $contextLines) {
                    $old[$inOld] = $i - 1;
                }

                $inOld = false;
            }
            ++$i;
        }

        $start = isset($old[0]) ? $old[0] : 0;
        $end   = count($diff);

        if ($tmp = array_search($end, $old)) {
            $end = $tmp;
        }

        $contextLinesCounter = 0;
        $contextPreSet       = false;
        $buffer              = $this->initBuffer();

        for ($i = $start; $i < $end; $i++) {
            if (isset($old[$i])) {
                $i = $old[$i];
            }

            if ($diff[$i][1] === 1 /* ADDED */) {
                $buffer[] = $this->highlightAdded($diff[$i][0]);
            } else if ($diff[$i][1] === 2 /* REMOVED */) {
                $buffer[]      = $this->highlightRemoved($diff[$i][0]);
                $contextPreSet = true;
            } else {
                if ($contextPreSet && $contextLinesCounter >= $contextLines) {
                    break;
                }
                $buffer[] = $this->highlightContext($diff[$i][0]);
                ++$contextLinesCounter;
            }
        }

        return $this->implodeBuffer($buffer);
    }

    /**
     * @return array
     */
    protected function initBuffer()
    {
        return array();
    }

    /**
     * @param string $line
     *
     * @return string
     */
    abstract protected function highlightAdded($line);

    /**
     * @param string $line
     *
     * @return string
     */
    abstract protected function highlightRemoved($line);

    /**
     * @param string $line
     *
     * @return string
     */
    abstract protected function highlightContext($line);

    /**
     * @param array $buffer
     *
     * @return string
     */
    abstract protected function implodeBuffer(array $buffer);
}
 