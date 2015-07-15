<?php

/**
 * Parses a a list of fields delimited by a single comma.
 *
 * @package    SqlParser
 * @subpackage Components
 */
namespace SqlParser\Components;

use SqlParser\Component;
use SqlParser\Parser;
use SqlParser\Token;
use SqlParser\TokensList;

/**
 * Parses a a list of fields delimited by a single comma.
 *
 * @category   Keywords
 * @package    SqlParser
 * @subpackage Components
 * @author     Dan Ungureanu <udan1107@gmail.com>
 * @license    http://opensource.org/licenses/GPL-2.0 GNU Public License
 */
class ExpressionArray extends Component
{

    /**
     * @param Parser     $parser  The parser that serves as context.
     * @param TokensList $list    The list of tokens that are being parsed.
     * @param array      $options Parameters for parsing.
     *
     * @return Expression[]
     */
    public static function parse(Parser $parser, TokensList $list, array $options = array())
    {
        $ret = array();

        $expr = null;

        for (; $list->idx < $list->count; ++$list->idx) {
            /**
             * Token parsed at this moment.
             * @var Token $token
             */
            $token = $list->tokens[$list->idx];

            // End of statement.
            if ($token->type === Token::TYPE_DELIMITER) {
                break;
            }

            // Skipping whitespaces and comments.
            if (($token->type === Token::TYPE_WHITESPACE) || ($token->type === Token::TYPE_COMMENT)) {
                continue;
            }

            if (($token->type === Token::TYPE_KEYWORD) && ($token->flags & Token::FLAG_KEYWORD_RESERVED)) {
                // No keyword is expected.
                break;
            }

            if (($token->type === Token::TYPE_OPERATOR) && ($token->value === ',')) {
                $ret[] = $expr;
            } else {
                $expr = Expression::parse($parser, $list, $options);
                if ($expr === null) {
                    break;
                }
            }

        }

        // Last iteration was not processed.
        if ($expr !== null) {
            $ret[] = $expr;
        }

        --$list->idx;
        return $ret;
    }

    /**
     * @param Expression[] $component The component to be built.
     *
     * @return string
     */
    public static function build($component)
    {
        $ret = array();
        foreach ($component as $frag) {
            $ret[] = $frag::build($frag);
        }
        return implode($ret, ', ');
    }
}
