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
 * @subpackage Mutant
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mutateme/blob/rewrite/LICENSE New BSD License
 */

namespace Mutagenesis\Mutant;

use Mutagenesis\Testee;
use Mutagenesis\Mutation;

class Mutant implements MutantInterface
{
    /**
     * @var Testee\TesteeInterface
     */
    protected $testee;

    /**
     * @var Mutation\MutationAbstract
     */
    protected $mutation;

    /**
     * @var bool
     */
    protected $captured = false;

    /**
     * @var string
     */
    protected $stdError;

    /**
     * @param Testee\TesteeInterface    $testee
     * @param Mutation\MutationAbstract $mutation
     */
    public function __construct(Testee\TesteeInterface $testee, Mutation\MutationAbstract $mutation)
    {
        $this->testee   = $testee;
        $this->mutation = $mutation;
    }

    /**
     * @return array
     */
    public function getTokens()
    {
        return $this->testee->getTokens();
    }

    /**
     * @return Mutation\MutationAbstract
     */
    public function getMutation()
    {
        return $this->mutation;
    }

    /**
     * @param bool $captured
     *
     * @return mixed
     */
    public function setCaptured($captured)
    {
        $this->captured = $captured;
    }

    /**
     * @return bool
     */
    public function isCaptured()
    {
        return $this->captured;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->testee->getFileName();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->testee->getClassName();
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->testee->getMethodName();
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->testee->getArguments();
    }

    /**
     * @return string
     */
    public function getDiff()
    {
        return $this->mutation->getDiff();
    }

    /**
     * Check the mutation actually mutates
     *
     * @return bool
     */
    public function checkDiff()
    {
        return $this->mutation->checkDiff();
    }

    /**
     * @param string $stdError
     *
     * @return Mutant
     */
    public function setStdError($stdError)
    {
        $this->stdError = $stdError;

        return $this;
    }

    /**
     * @return string
     */
    public function getStdError()
    {
        return $this->stdError;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array(
            'testee', 'mutation'
        );
    }

    /**
     * @return string
     */
    public function mutate()
    {
        return $this->getMutation()->mutate($this->testee->getTokens());
    }
}