<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use Slick\Common\Annotation\TokenParser\TokenList;
use Slick\Common\Annotation\TokenParser\Token;

/**
 * Class TokenParser
 *
 * Base on code from doctrine/annotations package
 * @see https://github.com/doctrine/annotations
 *
 * @package Slick\Common\Annotation
 * @author Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenParser
{

    /**
     * @var TokenList|Token[] The token list.
     */
    private $tokens;

    /**
     * @var int The number of tokens.
     */
    private $numTokens = 0;

    /**
     * @var int The current array pointer.
     */
    private $pointer = 0;

    /**
     * @param string $contents
     */
    public function __construct($contents)
    {
        $this->tokens = new TokenList($contents);
        $this->tokens = iterator_to_array(
            $this->tokens->getIterator(),
            false
        );
        $this->numTokens = count($this->tokens);
    }

    /**
     * Gets all use statements.
     *
     * @param string $namespaceName The namespace name of the reflected class.
     *
     * @return array A list with all found use statements.
     */
    public function parseUseStatements($namespaceName)
    {
        $statements = array();
        while (($token = $this->next())) {
            if ($token->is(T_USE)) {
                $statements = array_merge(
                    $statements, $this->parseUseStatement()
                );
                continue;
            }

            if ($this->checkNamespaceName($token, $namespaceName)) {
                continue;
            }
            // Get fresh array for new namespace. This is to prevent the parser
            // to collect the use statements for a previous namespace with the
            // same name. This is the case if a namespace is defined twice or
            // if a namespace with the same name is commented out.
            $statements = array();
        }
        return $statements;
    }

    /**
     * Gets the next non whitespace and non comment token.
     *
     * @return null|Token
     */
    public function next()
    {
        $token = null;
        for ($i = $this->pointer; $i < $this->numTokens; $i++) {
            $this->pointer++;
            if ($this->tokens[$i]->isWhiteSpaceOrComment()) {
                continue;
            }
            $token = $this->tokens[$i];
            break;
        }
        return $token;
    }

    /**
     * Parses a single use statement.
     *
     * @return array A list with all found class names for a use statement.
     */
    public function parseUseStatement()
    {
        $class = '';
        $alias = '';
        $statements = array();
        $explicitAlias = false;
        while ($token = $this->next()) {
            $isNameToken = $token->is([T_STRING, T_NS_SEPARATOR]);

            if (!$explicitAlias && $isNameToken) {
                $class .= $token->getValue();
                $alias = $token->getValue();
            } else if ($explicitAlias && $isNameToken) {
                $alias .= $token->getValue();
            } else if ($token->is(T_AS)) {
                $explicitAlias = true;
                $alias = '';
            } else if ($token->getValue() === ',') {
                $statements[$alias] = $class;
                $class = '';
                $alias = '';
                $explicitAlias = false;
            } else if ($token->getValue() === ';') {
                $statements[$alias] = $class;
                break;
            }
        }
        return $statements;
    }

    /**
     * Gets the namespace.
     *
     * @return string The found namespace.
     */
    public function parseNamespace()
    {
        $name = '';
        while (
            ($token = $this->next()) &&
            ($token->is([T_STRING, T_NS_SEPARATOR]))
        ) {
            $name .= $token->getValue();
        }
        return $name;
    }

    /**
     * Check if the provided namespace name is from different namespace class
     *
     * @param Token $token
     * @param string $namespaceName
     *
     * @return bool
     */
    private function checkNamespaceName(Token $token, $namespaceName)
    {
        return (
            ! $token->is(T_NAMESPACE) ||
            $this->parseNamespace() != $namespaceName
        );
    }
}