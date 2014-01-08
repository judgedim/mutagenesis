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

use Mutagenesis\Mutation;

interface MutantInterface
{
    /**
     * @return Mutation\MutationAbstract
     */
    public function getMutation();

    /**
     * @return array
     */
    public function getTokens();

    /**
     * @param bool $captured
     *
     * @return mixed
     */
    public function setCaptured($captured);

    /**
     * @param string $stdError
     *
     * @return mixed
     */
    public function setStdError($stdError);

    /**
     * @return bool
     */
    public function isCaptured();

    /**
     * @return string
     */
    public function getStdError();

    /**
     * @return string
     */
    public function mutate();

    /**
     * @return string
     */
    public function getFileName();

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getMethodName();

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return string
     */
    public function getDiff();
}