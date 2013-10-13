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
 * @subpackage Testee
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mutateme/blob/rewrite/LICENSE New BSD License
 * @author     Alexey Rusnak <alexx.rusnak@gmail.com>
 */

namespace Mutagenesis\Testee;

interface TesteeInterface
{
    /**
     * @return array
     */
    public function getTokens();

    /**
     * @param array $tokens
     * @return $this
     */
    public function setTokens(array $tokens);

    /**
     * @return bool
     */
    public function hasTokens();

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className);

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @param string $filename
     * @return $this
     */
    public function setFileName($filename);

    /**
     * @param string $methodName
     * @return $this
     */
    public function setMethodName($methodName);

    /**
     * @return string
     */
    public function getMethodName();
}