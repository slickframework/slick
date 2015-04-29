<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation\TokenParser;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Token List
 *
 * @package Slick\Common\Annotation\TokenParser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenList implements IteratorAggregate, Countable
{

    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * Creates the token list form provided code
     *
     * @param string $code The code where to retrieve the tokens
     */
    public function __construct($code)
    {
        $tokenData = token_get_all($code);
        // The PHP parser sets internal compiler globals for certain things.
        // Annoyingly, the last doc block comment it saw gets stored in
        // doc_comment. When it comes to compile the next thing to be
        // include()d this stored doc_comment becomes owned by the first thing
        // the compiler sees in the file that it considers might have a
        // doc block. If the first thing in the file is a class without a doc
        // block this would cause calls to
        // getDocBlock() on said class to return our long lost doc_comment.
        // Argh. To workaround, cause the parser to parse an empty doc block.
        // Sure getDocBlock() will return this, but at least  it's harmless
        // to us.
        token_get_all("<?php\n/**\n *\n */");
        foreach ($tokenData as $item) {
            $this->add(new Token($item));
        }
    }

    /**
     * Retrieve an external iterator for all tokens in the list
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable|Token[]|ArrayIterator The full tokens list
     */
    public function getIterator()
    {
        return new ArrayIterator($this->tokens);
    }

    /**
     * Count tokens in the list
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The total number of tokens in the list
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * Adds a token to the list
     *
     * @param Token $token The token object to add
     *
     * @return TokenList A self instance for method call chain
     */
    public function add(Token $token)
    {
        $this->tokens[] = $token;
        return $this;
    }
}