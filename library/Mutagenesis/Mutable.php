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

namespace Mutagenesis;

use Mutagenesis\Testee\Parser;
use Mutagenesis\Testee;
use Mutagenesis\Mutation\Factory\MutationFactory;
use Mutagenesis\Mutant\MutantCollection;
use Mutagenesis\Mutant\MutantCollectionInterface;
use Mutagenesis\Mutant\Mutant;

class Mutable
{
    /**
     * @var Parser\ParserInterface
     */
    protected $parser;

    /**
     * Name and relative path of the file to be mutated
     *
     * @var string
     */
    protected $filename = null;

    /**
     *  An array of generated mutants to be sequentially tested
     *
     * @var MutantCollectionInterface
     */
    protected $mutants;

    /**
     *  Array of mutable elements located in file
     *
     * @var array
     */
    protected $mutables = array();

    /**
     * @var MutantCollection
     */
    protected $mutantsEscaped;

    /**
     * @var MutantCollection
     */
    protected $mutantsCaptured;

    /**
     * Constructor; sets name and relative path of the file being mutated
     *
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        $this->setFilename($filename);
        $this->mutants = new MutantCollection();
    }

    /**
     * @return Parser\ParserInterface
     */
    public function getParser()
    {
        if (!$this->parser) {
            $this->parser = new Parser\Simple();
        }

        return $this->parser;
    }

    /**
     * Based on the current file, generate mutations
     *
     * @return $this
     */
    public function generate()
    {
        $this->mutables = $this->getParser()
            ->parseFile($this->getFilename());
        $this->parseTokensToMutations($this->mutables);

        return $this;
    }

    /**
     * Cleanup routines for memory management
     */
    public function cleanup()
    {
        $this->mutables = array();

        return $this;
    }

    /**
     * Set the file path of the file which is currently being assessed for
     * mutations.
     *
     * @param string $filename
     *
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Return the file path of the file which is currently being assessed for
     * mutations.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get an array of Class & Method indexed mutations containing the mutated
     * token and that token's index in the method's block code.
     *
     * @return MutantCollectionInterface
     */
    public function getMutants()
    {
        return $this->mutants;
    }

    /**
     * Get an array of method metainfo in tokenised form representing methods
     * which are capable of being mutated. Note: This does not guarantee they
     * will be mutated since this depends on the scope of supported mutations.
     *
     * @return array
     */
    public function getMutables()
    {
        return $this->mutables;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsCaptured()
    {
        return $this->mutants->getMutantsCaptured();
    }

    /**
     * @return \SplObjectStorage
     */
    public function getMutantsEscaped()
    {
        return $this->mutants->getMutantsEscaped();
    }

    /**
     * Check whether the current file will contain a mutation of the given type
     *
     * @param string $type The mutation type as documented
     *
     * @return bool
     */
    public function hasMutation($type)
    {
        $typeClass = '\\Mutagenesis\\Mutation\\' . $type;
        foreach ($this->getMutants() as $mutant) {
            if ($mutant->getMutation() instanceof $typeClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * Based on the internal array of mutable methods, generate another
     * internal array of supported mutations accessible using getMutants().
     *
     * @param array $testees
     *
     * @return void
     */
    protected function parseTokensToMutations(array $testees)
    {
        /**
         * @var \Mutagenesis\Testee\TesteeInterface $testee
         */
        foreach ($testees as $testee) {
            if (!$testee->hasTokens()) {
                continue;
            }
            foreach ($testee->getTokens() as $index => $token) {
                $mutation = MutationFactory::create($token, $index);
                if ($mutation !== false) {
                    $mutant = new Mutant($testee, $mutation);
                    $mutant->mutate();

                    if ($mutant->checkDiff()) {
                        $this->mutants->push($mutant);
                    }
                }
            }
        }
    }

}
