<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use ReflectionClass;
use SplFileObject;

/**
 * Parses a file for namespaces/use/class declarations.
 *
 * Based on PhpParser from doctrine/annotations package
 * @see https://github.com/doctrine/annotations
 *
 * @package Slick\Common\Annotation
 */
class PhpParser
{

    /**
     * Parses a class.
     *
     * @param ReflectionClass $class A ReflectionClass object.
     *
     * @return array A list with use statements in the form (Alias => FQN).
     */
    public function parseClass(ReflectionClass $class)
    {
        $content = $this->getFileContent(
            $class->getFilename(),
            $class->getStartLine()
        );

        $namespace = preg_quote($class->getNamespaceName());
        $content = preg_replace(
            '/^.*?(\bnamespace\s+' . $namespace . '\s*[;{].*)$/s',
            '\\1',
            $content
        );
        $tokenizer = new TokenParser('<?php ' . $content);
        return $tokenizer->parseUseStatements($class->getNamespaceName());
    }

    /**
     * Gets the content of the file right up to the given line number.
     *
     * @param string  $filename   The name of the file to load.
     * @param integer $lineNumber The number of lines to read from file.
     *
     * @return string The content of the file.
     */
    private function getFileContent($filename, $lineNumber)
    {
        $content = '';
        $lineCnt = 0;
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            if ($lineCnt++ == $lineNumber) {
                break;
            }
            $content .= $file->fgets();
        }
        return $content;
    }
}