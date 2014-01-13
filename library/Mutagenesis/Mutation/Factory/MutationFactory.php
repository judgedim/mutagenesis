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
 * @author     Dmitry Maltsev <judgedim@gmail.com>
 */

namespace Mutagenesis\Mutation\Factory;

class MutationFactory implements MutationFactoryInterface
{
    /**
     * @param string|array $token
     * @param int          $index
     *
     * @return \Mutagenesis\Mutation\MutationAbstract
     */
    public static function create($token, $index)
    {
        $mutationType = self::getMutationType($token);

        if ($mutationType) {
            $mutation = new $mutationType($index);

            return $mutation;
        }

        return false;
    }

    /**
     * Parse a given token (in string form) to identify its type and ascertain
     * whether it can be replaced with a mutated form. The mutated form, if
     * any, is returned for future integration into a mutated version of the
     * source code being tested.
     *
     * @param string $token The token to check for viable mutations
     *
     * @return string|bool Return false if no mutation, or a mutation type
     */
    protected static function parseStringToken($token)
    {
        switch ($token) {
            case '+':
                return '\Mutagenesis\Mutation\OperatorAddition';
            case '-':
                return '\Mutagenesis\Mutation\OperatorSubtraction';
            case '/':
                return '\Mutagenesis\Mutation\OperatorArithmeticDivision';
            case '%':
                return '\Mutagenesis\Mutation\OperatorArithmeticModulus';
            case '*':
                return '\Mutagenesis\Mutation\OperatorArithmeticMultiplication';
            case '|':
                return '\Mutagenesis\Mutation\OperatorBitwiseOr';
            case '^':
                return '\Mutagenesis\Mutation\OperatorBitwiseXor';
            case '~':
                return '\Mutagenesis\Mutation\OperatorBitwiseNot';
            case '!':
                return '\Mutagenesis\Mutation\OperatorLogicalNot';
            case '<':
                return '\Mutagenesis\Mutation\OperatorComparisonLessThan';
            case '>':
                return '\Mutagenesis\Mutation\OperatorComparisonGreaterThan';
        }
        return false;
    }

    /**
     * Parse a given token (in array form) to identify its type and ascertain
     * whether it can be replaced with a mutated form. The mutated form, if
     * any, is returned for future integration into a mutated version of the
     * source code being tested.
     *
     * @param array $token The token to check for viable mutations
     *
     * @return string|bool Return false if no mutation, or a mutation type
     */
    protected static function parseToken(array $token)
    {
        switch ($token[0]) {
            case T_INC:
                return '\Mutagenesis\Mutation\OperatorIncrement';
            case T_DEC:
                return '\Mutagenesis\Mutation\OperatorDecrement';
            case T_PLUS_EQUAL:
                return '\Mutagenesis\Mutation\OperatorAssignmentPlusEqual';
            case T_MINUS_EQUAL:
                return '\Mutagenesis\Mutation\OperatorAssignmentMinusEqual';
            case T_SL:
                return '\Mutagenesis\Mutation\OperatorBitwiseShiftLeft';
            case T_SR:
                return '\Mutagenesis\Mutation\OperatorBitwiseShiftRight';
            case T_IS_EQUAL:
                return '\Mutagenesis\Mutation\OperatorComparisonEqual';
            case T_IS_IDENTICAL:
                return '\Mutagenesis\Mutation\OperatorComparisonIdentical';
            case T_IS_NOT_EQUAL:
                return '\Mutagenesis\Mutation\OperatorComparisonNotEqual';
            case T_IS_NOT_IDENTICAL:
                return '\Mutagenesis\Mutation\OperatorComparisonNotIdentical';
            case T_IS_SMALLER_OR_EQUAL:
                return '\Mutagenesis\Mutation\OperatorComparisonLessThenOrEqualTo';
            case T_IS_GREATER_OR_EQUAL:
                return '\Mutagenesis\Mutation\OperatorComparisonGreaterThanOrEqualTo';
            case T_BOOLEAN_AND:
                return '\Mutagenesis\Mutation\BooleanAnd';
            case T_LOGICAL_AND:
                return '\Mutagenesis\Mutation\OperatorLogicalAnd';
            case T_BOOLEAN_OR:
                return '\Mutagenesis\Mutation\BooleanOr';
            case T_LOGICAL_OR:
                return '\Mutagenesis\Mutation\OperatorLogicalOr';
            case T_LOGICAL_XOR:
                return '\Mutagenesis\Mutation\OperatorLogicalXor';
            case T_CONSTANT_ENCAPSED_STRING:
                return '\Mutagenesis\Mutation\ScalarString';
            case T_LNUMBER:
                return '\Mutagenesis\Mutation\ScalarInteger';
            case T_DNUMBER:
                return '\Mutagenesis\Mutation\ScalarFloat';
            case T_IF:
            case T_ELSEIF:
                return '\Mutagenesis\Mutation\LogicalIf';
            case T_STRING:
                return self::parseTString($token);
        }

        return false;
    }

    /**
     * Parse a T_STRING value to identify a possible mutation type
     *
     * @param array $token
     *
     * @return string
     */
    protected static function parseTString(array $token)
    {
        if (strtolower($token[1]) == 'true') {
            return '\Mutagenesis\Mutation\BooleanTrue';
        } elseif (strtolower($token[1]) == 'false') {
            return '\Mutagenesis\Mutation\BooleanFalse';
        }

        return false;
    }

    /**
     * @param string|array $token
     *
     * @return string|bool
     */
    protected static function getMutationType($token)
    {
        if (is_string($token)) {
            return self::parseStringToken($token);
        } elseif (is_array($token) && count($token) === 3) {
            return self::parseToken($token);
        }

        return false;
    }
}
 