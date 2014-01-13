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

namespace Mutagenesis\Mutation;

use Mutagenesis\Utility\Diff;

abstract class MutationAbstract
{
    /**
     * @var int
     */
    protected $index;

    /**
     * Array of original source code tokens prior to mutation
     *
     * @var array
     */
    protected $tokensOriginal = array();

    /**
     * Array of source code tokens after a mutation has been applied
     *
     * @var array
     */
    protected $tokensMutated = array();

    /**
     * Diff provider instance.
     *
     * @var Diff\ProviderInterface
     */
    protected $diffProvider;

    /**
     * Constructor; sets index
     *
     * @param int $index
     */
    public function __construct($index)
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Return the diff provider.
     *
     * @return Diff\ProviderInterface
     */
    public function getDiffProvider()
    {
        if (!$this->diffProvider) {
            $this->diffProvider = new Diff\PhpUnit();
        }

        return $this->diffProvider;
    }

    /**
     * Set the diff provider.
     *
     * @param Diff\ProviderInterface $provider
     *
     * @return $this
     */
    public function setDiffProvider(Diff\ProviderInterface $provider)
    {
        $this->diffProvider = $provider;

        return $this;
    }

    /**
     * Perform a mutation against the given original source code tokens for
     * a mutable element
     *
     * @param array $tokens
     *
     * @return string
     */
    public function mutate($tokens)
    {
        $this->tokensOriginal = $tokens;
        $this->tokensMutated  = $this->getMutation($this->tokensOriginal, $this->getIndex());

        return $this->reconstructFromTokens($this->tokensMutated);
    }

    /**
     * Calculate the unified diff between the original source code and its
     * its mutated form
     *
     * @return string
     */
    public function getDiff()
    {
        if (!$this->checkDiff()) {
            return '';
        }

        $original = $this->reconstructFromTokens($this->tokensOriginal);
        $mutated  = $this->reconstructFromTokens($this->tokensMutated);

        return $this->getDiffProvider()->difference($original, $mutated);
    }

    /**
     * Check the mutation actually mutates
     *
     * @return bool
     */
    public function checkDiff()
    {
        if ($this->tokensOriginal !== $this->tokensMutated) {
            return true;
        }

        return false;
    }

    /**
     * Get a new mutation as an array of changed tokens
     *
     * @param array $tokens
     * @param int   $index
     *
     * @return array
     */
    abstract public function getMutation(array $tokens, $index);

    /**
     * Reconstruct a new mutation into a source code string based on the
     * returned tokens
     *
     * @param array $tokens
     *
     * @return string
     */
    protected function reconstructFromTokens(array $tokens)
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