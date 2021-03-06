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
 * @author     Alexey Rusnak <alexx.rusnak@gmail.com>
 */

namespace Mutagenesis\Utility\Diff;

/**
 * PhpUnit diff provider implementation.
 *
 * @package Mutagenesis\Utility\Diff
 * @see     Mutagenesis\Utility\Diff\ProviderInterface
 */
class PhpUnit extends PhpUnitAbstract implements ProviderInterface
{
    /**
     * @return array
     */
    protected function initBuffer()
    {
        return array(
            "--- Original",
            "+++ New",
            "@@ @@"
        );
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightAdded($line)
    {
        return '+' . $line;
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightRemoved($line)
    {
        return '-' . $line;
    }

    /**
     * @param string $line
     *
     * @return string
     */
    protected function highlightContext($line)
    {
        return ' ' . $line;
    }

    /**
     * @param array $buffer
     *
     * @return string
     */
    protected function implodeBuffer(array $buffer)
    {
        return implode("\n", $buffer) . "\n";
    }


}