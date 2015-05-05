<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation\TokenParser;

/**
 * Parses tokens to retrieve the use statements class and alias names
 *
 * @package Slick\Common\Annotation\TokenParser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UseStatementFactory
{

    /**
     * @var string Holds current alias name
     */
    private $alias;

    /**
     * @var string Holds current class name
     */
    private $class;

    /**
     * @var bool Flag for parsing done
     */
    private $done = false;

    /**
     * @var bool Flag to set next token as an alias name
     */
    private $explicitAlias = false;

    /**
     * @var array<string, string> Associative array with alias/class pairs
     */
    private $list = [];

    /**
     * Check if parsing is done
     *
     * @return bool True if end of sentence is found on token
     */
    public function isDone()
    {
        return $this->done;
    }

    /**
     * Returns the parsed use statements associative array
     *
     * @return array<string, string> Associative array with alias/class pairs
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Start parsing chain of a token
     *
     * If is a string or a namespace separator it concatenates it to de end of
     * the class name and sets the alias with it.
     *
     * Otherwise it passes the parsing responsibility to alias name parsing
     * method UseStatementFactory::parseAliasName()
     *
     * @see UseStatementFactory::parseAliasName()
     *
     * @param Token $token Current parsing token
     *
     * @return UseStatementFactory A self instance for method calls chaining
     */
    public function addToken(Token $token)
    {
        $isNameToken = $token->is([T_STRING, T_NS_SEPARATOR]);
        if (!$this->explicitAlias && $isNameToken) {
            $this->class .= $token->getValue();
            $this->alias = $token->getValue();
            return $this;
        }

        return $this->parseAliasName($token);
    }

    /**
     * Parse token to check alias name usage
     *
     * If previous token was an "as" (T_AS) explicit alias token then this
     * method sets the alias name to the token value.
     *
     * Otherwise it passes the parsing responsibility to alias token checking
     * method UseStatementFactory::checkExplicitAlias()
     *
     * @see UseStatementFactory::checkExplicitAlias()
     *
     * @param Token $token Current parsing token
     *
     * @return UseStatementFactory A self instance for method calls chaining
     */
    private function parseAliasName(Token $token)
    {
        $isNameToken = $token->is([T_STRING, T_NS_SEPARATOR]);
        if ($this->explicitAlias && $isNameToken) {
            $this->alias .= $token->getValue();
            return $this;
        }

        return $this->checkExplicitAlias($token);
    }

    /**
     * Check if token is an explicit alias token
     *
     * If is a "as" (T_AS) explicit alias token it will set
     * UseStatementFactory::$explicitAlias property to boolean true.
     * If not, it passes the parsing responsibility to comma separated use
     * statements checking method
     * UseStatementFactory::checkCommaSeparatedNames().
     *
     * @see UseStatementFactory::checkCommaSeparatedNames()
     *
     * @param Token $token Current parsing token
     *
     * @return UseStatementFactory A self instance for method calls chaining
     */
    private function checkExplicitAlias(token $token)
    {
        if ($token->is(T_AS)) {
            $this->explicitAlias = true;
            $this->alias = '';
            return $this;
        }

        return $this->checkCommaSeparatedNames($token);
    }

    /**
     * Check if token is a comma
     *
     * This token states that another class and alias will follow so this
     * method will add current class/alias data to the statements list
     * and clean that data for next incoming token.
     *
     * If its not, it passes the parsing responsibility to end use statement
     * checking method
     * UseStatementFactory::checkEndUseStatement().
     *
     * @see UseStatementFactory::checkEndUseStatement()
     *
     * @param Token $token Current parsing token
     *
     * @return UseStatementFactory A self instance for method calls chaining
     */
    private function checkCommaSeparatedNames(Token $token)
    {
        if ($token->getValue() === ',') {
            $this->list[$this->alias] = $this->class;
            $this->alias = '';
            $this->class = '';
            $this->explicitAlias = false;
            return $this;
        }

        return $this->checkEndUseStatement($token);
    }

    /**
     * Check if an end use statement token
     *
     * If is a ';' value it will ser the factory done flag to boolean true
     * and add current parsed data to statements list. This is also the
     * final parser/checker in the current loop cycle.
     *
     * @param Token $token Current parsing token
     *
     * @return UseStatementFactory A self instance for method calls chaining
     */
    private function checkEndUseStatement(Token $token)
    {
        if ($token->getValue() === ';') {
            $this->list[$this->alias] = $this->class;
            $this->done = true;
        }
        return $this;
    }
}