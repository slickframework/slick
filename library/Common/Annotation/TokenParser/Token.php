<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation\TokenParser;

/**
 * A PHP Token wrapper
 *
 * @package Slick\Common\Annotation\TokenParser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Token
{

    /**
     * @see http://php.net/manual/en/tokens.php
     * @var int PHP token index
     */
    private $index;

    /**
     * @var string[]|string
     */
    private $value;

    /**
     * @var string
     */
    private $lineNumber;

    /**
     * Creates a token from provided dada array
     *
     * @see http://php.net/manual/en/function.token-get-all.php
     *
     * @param string[]|string $tokenData
     */
    public function __construct($tokenData)
    {
        $data = [null, $tokenData, null];
        if (is_array($tokenData)) {
            $data = array_replace($data, $tokenData);
        }

        /** @var string[]|string $value */
        list($index, $value, $lineNumber) = $data;
        $this->index = $index;
        $this->value = $value;
        $this->lineNumber = $lineNumber;
    }

    /**
     * Get the symbolic name of current PHP token
     *
     * @see http://php.net/manual/en/function.token-name.php
     *
     * @return string
     */
    public function getName()
    {
        return token_name($this->getIndex());
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Check if this token is a white space or comment
     *
     * @return bool
     */
    public function isWhiteSpaceOrComment()
    {
        $names = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT];
        return in_array($this->getIndex(), $names);
    }

    /**
     * Check if this token is on of the provided token indexes
     *
     * @param string[]|string $tokenNames
     *
     * @return bool
     */
    public function is($tokenNames)
    {
        $check = [$tokenNames];
        if (is_array($tokenNames)) {
            $check = $tokenNames;
        }
        return in_array($this->getIndex(), $check);
    }

}