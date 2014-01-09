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

interface MutantCollectionInterface extends \IteratorAggregate
{
    /**
     * @return \SplObjectStorage
     */
    public function all();

    /**
     * @param MutantInterface $mutant
     *
     * @return MutantCollectionInterface
     */
    public function push(MutantInterface $mutant);

    /**
     * @return int
     */
    public function count();

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsEscaped();

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsCaptured();
}
