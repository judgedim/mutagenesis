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

namespace Mutagenesis\Testee\Parser;

use Mutagenesis\Testee\ClassMethod as ClassMethodTestee;

class Simple implements ParserInterface
{
    /**
     * @param string $path
     * @return array
     */
    public function parseFile($path)
    {
        $tokens = token_get_all(file_get_contents($path));
        $inblock = false;
        $inarg = false;
        $curlycount = 0;
        $roundcount = 0;
        $blockTokens = array();
        $argTokens = array();
        $methods = array();
        $staticClassCapture = true;
        foreach ($tokens as $index=>$token) {
            if(is_array($token) && $token[0] == T_STATIC && $staticClassCapture === true) {
                $staticClassCapture = false;
                continue;
            }
            // get class name
            if (is_array($token) && ($token[0] == T_CLASS || $token[0] == T_INTERFACE)) {
                $className = $tokens[$index+2][1];
                $staticClassCapture = false;
                continue;
            }
            // get method name
            if (is_array($token) && $token[0] == T_FUNCTION) {
                //Anonymous function
                if (!isset($tokens[$index+2][1])) {
                    continue;
                }
                $methodName = $tokens[$index+2][1];
                $inarg = true;
                $mutable = new ClassMethodTestee();
                $mutable->setFileName($path)
                        ->setClassName($className)
                        ->setMethodName($methodName);
                continue;
            }
            // Get the method's parameter string
            if ($inarg) {
                if ($token == '(') {
                    $roundcount += 1;
                } elseif ($token == ')') {
                    $roundcount -= 1;
                }
                if ($roundcount == 1 && $token == '(') {
                    continue;
                } elseif ($roundcount >= 1) {
                    $argTokens[] = $token;
                } elseif ($roundcount == 0) {
                    $mutable->setArguments($this->_reconstructFromTokens($argTokens));
                    $argTokens = array();
                    $inarg = false;
                    $inblock = true;
                }
                continue;
            }
            // Get the method's block code
            if ($inblock) {
                if ($token == '{') {
                    $curlycount += 1;
                } elseif ($token == '}') {
                    $curlycount -= 1;
                }
                if ($curlycount == 1 && $token == '{') {
                    continue;
                } elseif ($curlycount >= 1) {
                    if (is_array($token) && $token[0] == 370) {
                        continue;
                    }
                    $blockTokens[] = $token;
                } elseif ($curlycount == 0 && count($blockTokens) > 0) {
                    $mutable->setTokens($blockTokens);
                    $methods[] = $mutable;
                    $mutable = array();
                    $blockTokens = array();
                    $inblock = false;
                    $staticClassCapture = true;
                }
            }
        }
        return $methods;
    }

    /**
     * Reconstruct a string of source code from its constituent tokens
     *
     * @param array $tokens
     * @return string
     */
    protected function _reconstructFromTokens(array $tokens)
    {
        $str = '';
        foreach ($tokens as $token) {
            if (is_string($token)) {
                $str .= $token;
            } else {
                $str .= $token[1];
            }
        }
        return $str;
    }
}