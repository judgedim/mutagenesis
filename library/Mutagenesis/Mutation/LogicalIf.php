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

class LogicalIf extends MutationAbstract
{
    protected $excludeStrings = array('+', '-');
    protected $excludeTokens = array(T_BOOLEAN_AND, T_BOOLEAN_OR);
    protected $excludeTStrings = array("true", "false");

    /**
     * Wrap a conditional with a !
     *
     * @param array $tokens
     * @param int $index
     * @return array
     */
    public function getMutation(array $tokens, $index)
    {
        $parenCount = false;
        $newTokens = array_slice($tokens, 0, $index);

        while($parenCount !== 0) {

            if (!isset($tokens[$index])) {
                // uh oh, we've not matched parens correctly
                return $tokens;
            }

            $token = $tokens[$index];

            /**
             * Short circuit, other mutations will be more specific
             */
            if (is_array($token) && in_array($token[0], $this->excludeTokens)) {
                return $tokens;
            }

            if (is_array($token) && $token[0] == T_STRING && in_array($token[1], $this->excludeTStrings)) {
                return $tokens;
            }

            if (!is_array($token) && in_array($token, $this->excludeStrings)) {
                return $tokens;
            }

            /**
             * If first parenthesis, add our code
             */
            if ($token == '(' && $parenCount === false) {
                $newTokens[] = '(';
                $newTokens[] = '!';
            }

            /**
             * Watch the parenthesis count
             */
            if ($token == '(') {
                $parenCount = intval($parenCount) + 1;
            }

            if ($token == ')') {
                $parenCount--;
            }

            $newTokens[] = $token;
            $index++;
        }

        /**
         * Add our new code and the remainder of the original
         */
        $newTokens[] = ')';
        $newTokens = array_merge($newTokens, array_slice($tokens, $index));
        return $newTokens;
    }

}
