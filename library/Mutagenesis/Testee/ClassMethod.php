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

class ClassMethod implements TesteeInterface
{
    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $tokens = array();

    /**
     * @var string
     */
    protected $arguments;

    /**
     * @var string
     */
    protected $className;

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFileName($filename)
    {
        $this->fileName = $filename;
        return $this;
    }

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param array $tokens
     * @return $this
     */
    public function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $methodName
     * @return $this
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return bool
     */
    public function hasTokens()
    {
        return !empty($this->tokens);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'tokens'    => $this->getTokens(),
            'file'      => $this->getFileName(),
            'class'     => $this->getClassName(),
            'method'    => $this->getMethodName(),
            'args'      => $this->getArguments()
        );
    }
}