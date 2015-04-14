<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

/**
 * Parses doc block comments to retrieve the annotations values
 *
 * @package Slick\Common\Annotation
 */
class Parser
{
    /**#@+
     * @var string Annotation related regular expression
     * @codingStandardsIgnoreStart
     */
    const ANNOTATION_REGEX = '/@(\w+)(?:\s*(?:\(\s*)?(.*?)(?:\s*\))?)??\s*(?:\n|\*\/)/';
    const ANNOTATION_PARAMETERS_REGEX = '/([\w]+\s*=\s*[\[\{"]{1}[\w,\\\\\s:\."\{\[\]\}]+[\}\]""]{1})|([\w]+\s*=\s*[\\\\\w\.]+)|([\\\\\w]+)/i';
    /**#@-*/
    // @codingStandardsIgnoreEnd

    /**
     * @see ReflectionClass::getDocComment()
     * @var string The comment to be parsed
     */
    private $comment;

    /**
     * @var string[] A list of comment tags
     */
    private $tags;

    /**
     * Creates a parser for provided comment
     *
     * @param string $comment The comment to be parsed
     */
    public function __construct($comment = null)
    {
        $this->setComment($comment);
    }

    /**
     * Retrieves a list of annotations in the provided comment.
     *
     * @return string[]|array[] A associative array where keys are annotation
     *  names and values can be a string for simple annotations or another
     *  associative array with key/value pairs for annotations with parameters.
     */
    public function getAnnotationData()
    {
        $tags = $this->getTags();
        $annotationData = [];
        foreach ($tags as $tag) {
            $name = $tag[1];        // Annotation name
            $value = true;          // Default annotation value

            $annotationData[$name] = $value;
        }
        return $annotationData;
    }

    /**
     * Returns the list of tags in the current comment string
     *
     * @return array|\string[] An array of strings or an empty array if no tags
     *  are present in comment string
     */
    public function getTags()
    {
        if (is_null($this->tags)) {
            $tags = preg_match_all(
                self::ANNOTATION_REGEX,
                $this->comment,
                $matches,
                PREG_SET_ORDER
            );
            $this->tags = (!$tags) ? [] : $matches;
        }
        return $this->tags;
    }

    /**
     * Set the comment to parse
     *
     * This method will also clean up the tags list.
     *
     * @see Parser::$tags
     *
     * @param string $comment The comment to be parsed
     * @return Parser Current instance for easy method call chaining
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        $this->tags = null;
        return $this;
    }
}
