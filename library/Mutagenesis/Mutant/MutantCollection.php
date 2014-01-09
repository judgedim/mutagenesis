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
 * @author     Dmitry Maltsev <judgedim@gmail.com>
 */

namespace Mutagenesis\Mutant;

class MutantCollection implements MutantCollectionInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $mutants;

    public function __construct()
    {
        $this->mutants = new \SplObjectStorage();
    }

    /**
     * @return \SplObjectStorage
     */
    public function all()
    {
        return $this->mutants;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsCaptured()
    {
        $collection = new \SplObjectStorage;
        /** @var MutantInterface $mutant */
        foreach ($this->mutants as $mutant) {
            if ($mutant->isCaptured()) {
                $collection->attach($mutant);
            }
        }

        return $collection;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsEscaped()
    {
        $collection = new \SplObjectStorage;
        /** @var MutantInterface $mutant */
        foreach ($this->mutants as $mutant) {
            if (!$mutant->isCaptured()) {
                $collection->attach($mutant);
            }
        }

        return $collection;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getIterator()
    {
        return $this->mutants;
    }

    /**
     * @param MutantInterface $mutant
     *
     * @return MutantCollection
     */
    public function push(MutantInterface $mutant)
    {
        $this->mutants->attach($mutant);

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->mutants->count();
    }

}
