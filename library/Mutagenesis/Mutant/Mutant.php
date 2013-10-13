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
     * @var array
     */
    protected $mutations = array();

    /**
     * @param Testee\TesteeInterface    $testee
     * @param Mutation\MutationAbstract $mutation
     */
    public function __construct(Testee\TesteeInterface $testee, Mutation\MutationAbstract $mutation)
    {
        $this->testee = $testee;
        $this->mutations[] = $mutation;
    }

    /**
     * @return Testee\TesteeInterface
     */
    public function getTestee()
    {
        return $this->testee;
    }

    /**
     * @return array
     */
    public function getMutations()
    {
        return $this->mutations;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return array(
            'index', 'testee', 'mutations'
        );
    }
}