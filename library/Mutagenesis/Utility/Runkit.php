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

namespace Mutagenesis\Utility;

use Mutagenesis\Mutant\MutantInterface;

class Runkit
{
    /**
     * Method signature hash appended to a replaced method's name so it can
     * be reinstated later without any need to separately store entire method
     * related code blocks.
     *
     * @var string
     */
    protected $_methodPreserveCode = '';

    /**
     * Apply a mutation to the relevant file
     *
     * @param MutantInterface $mutant
     *
     * @throws \Exception
     */
    public function applyMutation(MutantInterface $mutant)
    {
        $class    = $mutant->getClassName();
        $method   = $mutant->getMethodName();
        $args     = $mutant->getArguments();
        $filename = $mutant->getFileName();

        require_once $filename;

        $newBlock                  = $mutant->mutate();
        $this->_methodPreserveCode = md5($method);
        if (runkit_method_rename($class, $method, $method . $this->_methodPreserveCode) == false) {
            throw new \Exception(
                'runkit_method_rename() failed from ' . $class
                . '::' . $method . ' to ' . $class
                . '::' . $method . $this->_methodPreserveCode
                . ' (mutation application)'
            );
        }
        if (runkit_method_add($class, $method, $args, $newBlock, $this->getMethodFlags($mutant)) == false) {
            throw new \Exception(
                'runkit_method_add() failed when replacing original '
                . $class . '::' . $method
                . '(' . var_export($args) . ') with a mutation of'
                . ' type ' . get_class($mutant->getMutation()) . ' using the'
                . ' following (mutated) source code from '
                . $filename . ':' . PHP_EOL
                . $newBlock
            );
        }
    }

    /**
     * Reverse a previously applied mutation to the given file
     *
     * @param MutantInterface $mutant
     *
     * @throws \Exception
     */
    public function reverseMutation(MutantInterface $mutant)
    {
        $class  = $mutant->getClassName();
        $method = $mutant->getMethodName();

        if (runkit_method_remove($class, $method) == false) {
            throw new \Exception(
                'runkit_method_remove() failed attempting to remove '
                . $class . '::' . $method
            );
        }
        if (runkit_method_rename($class, $method . $this->_methodPreserveCode, $method) == false) {
            throw new \Exception(
                'runkit_method_rename() failed renaming from '
                . $class . '::' . $method
                . $this->_methodPreserveCode . ' to ' . $class
                . '::' . $method . ' (mutation reversal)'
            );
        }
    }

    /**
     * Get the appropriate ext/runkit method flag value to use during
     * a replacement via the runkit methods
     *
     * @param MutantInterface $mutant
     *
     * @return int
     */
    public function getMethodFlags(MutantInterface $mutant)
    {
        $reflectionClass  = new \ReflectionClass($mutant->getClassName());
        $reflectionMethod = $reflectionClass->getMethod(
            $mutant->getMethodName() . $this->_methodPreserveCode
        );
        $static           = null;
        $access           = null;
        if ($reflectionMethod->isPublic()) {
            $access = RUNKIT_ACC_PUBLIC;
        } elseif ($reflectionMethod->isProtected()) {
            $access = RUNKIT_ACC_PROTECTED;
        } elseif ($reflectionMethod->isPrivate()) {
            $access = RUNKIT_ACC_PRIVATE;
        }
        if (defined('RUNKIT_ACC_STATIC') && $reflectionMethod->isStatic()) {
            $static = RUNKIT_ACC_STATIC;
        }
        if (!is_null($static)) {
            return $access | $static;
        }

        return $access;
    }

}
