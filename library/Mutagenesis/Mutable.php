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
     *  An array of generated mutations to be sequentially tested
     *
     * @var array
     */
    protected $mutations = array();

    /**
     *  Array of mutable elements located in file
     *
     * @var array
     */
    protected $mutables = array();

    /**
     * Constructor; sets name and relative path of the file being mutated
     *
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        $this->setFilename($filename);
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
        $this->_parseTokensToMutations($this->mutables);
        return $this;
    }

    /**
     * Cleanup routines for memory management
     */
    public function cleanup()
    {
        $this->mutations = $this->mutables = array();
        return $this;
    }

    /**
     * Set the file path of the file which is currently being assessed for
     * mutations.
     *
     * @param string $filename
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
     * @return array
     */
    public function getMutations()
    {
        return $this->mutations;
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
     * Check whether the current file will contain a mutation of the given type
     *
     * @param string $type The mutation type as documented
     * @return bool
     */
    public function hasMutation($type)
    {
        $typeClass = '\\Mutagenesis\\Mutation\\' . $type;
        foreach ($this->getMutations() as $mutation) {
            if ($mutation['mutation'] instanceof $typeClass) {
                return true;
            }
        }
        return false;
    }

    /**
     * Based on the internal array of mutable methods, generate another
     * internal array of supported mutations accessible using getMutations().
     *
     * @param array $mutables
     * @return void
     */
    protected function _parseTokensToMutations(array $mutables)
    {
        foreach ($mutables as $method) {
            $method = $method->toArray();
            if (!isset($method['tokens']) || empty($method['tokens'])) {
                continue;
            }
            foreach ($method['tokens'] as $index=>$token) {
                if (is_string($token)) {
                    $mutation = $this->_parseStringToken($token);
                } else {
                    $mutation = $this->_parseToken($token);
                }
                if (!is_null($mutation)) {
                    $this->mutations[] = $method + array(
                        'index' => $index,
                        'mutation' => $mutation
                    );
                }
            }
        }
    }

    /**
     * Parse a given token (in string form) to identify its type and ascertain
     * whether it can be replaced with a mutated form. The mutated form, if
     * any, is returned for future integration into a mutated version of the
     * source code being tested.
     *
     * @param array $token The token to check for viable mutations
     * @return mixed Return null if no mutation, or a mutation object
     */
    protected function _parseStringToken($token)
    {
        $type = '';
        switch ($token) {
            case '+':
                $type = 'OperatorAddition';
                break;
            case '-':
                $type = 'OperatorSubtraction';
                break;
        }
        if (!empty($type)) {
            $mutationClass =  'Mutagenesis\\Mutation\\' . $type;
            $mutation = new $mutationClass($this->getFilename());
            return $mutation;
        }
    }

    /**
     * Parse a given token (in array form) to identify its type and ascertain
     * whether it can be replaced with a mutated form. The mutated form, if
     * any, is returned for future integration into a mutated version of the
     * source code being tested.
     *
     * @param array $token The token to check for viable mutations
     * @return mixed Return null if no mutation, or a mutation object
     */
    protected function _parseToken(array $token)
    {
        $type = '';
        switch ($token[0]) {
            case T_INC:
                $type = 'OperatorIncrement';
                break;
            case T_DEC:
                $type = 'OperatorDecrement';
                break;
            case T_BOOLEAN_AND:
                $type = 'BooleanAnd';
                break;
            case T_BOOLEAN_OR:
                $type = 'BooleanOr';
                break;
            case T_STRING:
                $type = $this->_parseTString($token);
                break;
        }
        if (!empty($type)) {
            $mutationClass =  'Mutagenesis\\Mutation\\' . $type;
            $mutation = new $mutationClass($this->getFilename());
            return $mutation;
        }
        return null;
    }

    /**
     * Parse a T_STRING value to identify a possible mutation type
     *
     * @param array $token
     * @return string
     */
    public function _parseTString(array $token)
    {
        $type = null;
        if (strtolower($token[1]) == 'true') {
            $type = 'BooleanTrue';
        } elseif (strtolower($token[1]) == 'false') {
            $type = 'BooleanFalse';
        }
        return $type;
    }
}