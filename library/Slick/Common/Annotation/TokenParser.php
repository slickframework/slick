<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

/**
 * Class TokenParser
 *
 * Code from doctrine/annotations package
 * @see https://github.com/doctrine/annotations
 *
 * @package Slick\Common\Annotation
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Christian Kaps <christian.kaps@mohiva.com>
 */
class TokenParser
{

    /**
     * @var array The token list.
     */
    private $tokens;

    /**
     * @var int The number of tokens.
     */
    private $numTokens;

    /**
     * @var int The current array pointer.
     */
    private $pointer = 0;

    /**
     * @param string $contents
     */
    public function __construct($contents)
    {
        $this->tokens = token_get_all($contents);
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
            if ($token[0] === T_USE) {
                $statements = array_merge(
                    $statements, $this->parseUseStatement()
                );
                continue;
            }

            if (
                $token[0] !== T_NAMESPACE ||
                $this->parseNamespace() != $namespaceName
            ) {
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
     * @return array|null The token if exists, null otherwise.
     */
    public function next()
    {
        $token = null;
        for ($i = $this->pointer; $i < $this->numTokens; $i++) {
            $this->pointer++;
            $ignoreToken = (
                $this->tokens[$i][0] === T_WHITESPACE ||
                $this->tokens[$i][0] === T_COMMENT ||
                $this->tokens[$i][0] === T_DOC_COMMENT
            );

            if ($ignoreToken) {
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
            $isNameToken = (
                $token[0] === T_STRING ||
                $token[0] === T_NS_SEPARATOR
            );
            if (!$explicitAlias && $isNameToken) {
                $class .= $token[1];
                $alias = $token[1];
            } else if ($explicitAlias && $isNameToken) {
                $alias .= $token[1];
            } else if ($token[0] === T_AS) {
                $explicitAlias = true;
                $alias = '';
            } else if ($token === ',') {
                $statements[$alias] = $class;
                $class = '';
                $alias = '';
                $explicitAlias = false;
            } else if ($token === ';') {
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
            ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)
        ) {
            $name .= $token[1];
        }
        return $name;
    }
}