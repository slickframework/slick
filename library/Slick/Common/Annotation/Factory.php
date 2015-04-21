<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use ReflectionClass;
use Slick\Common\Exception\InvalidAnnotationClassException;
use Slick\Common\Exception\InvalidArgumentException;

/**
 * Creates annotations from comment texts
 *
 * @package Slick\Common\Annotation
 */
class Factory
{

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * @var string The default annotation class for factory
     */
    private static $defaultClass = "Slick\\Common\\Annotation\\Basic";

    /**
     * Creates a list of annotations from provided comment text
     *
     * @param string $comment
     * @return AnnotationList
     */
    public function getAnnotationsFor($comment)
    {
        $data = $this->getParser()
            ->setComment($comment)
            ->getAnnotationData();

        $list = new AnnotationList();
        foreach ($data as $name => $parsed) {
            $list->append($this->createAnnotationFor($name, $parsed));
        }
        return $list;
    }

    /**
     * Sets comment tag parser
     *
     * @param Parser $parser
     *
     * @return AnnotationList
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    /**
     * Retrieves current parser comment parser
     *
     * @return Parser
     */
    public function getParser()
    {
        if (is_null($this->parser)) {
            $this->setParser(new Parser());
        }
        return $this->parser;
    }

    /**
     * Sets class reflection object
     *
     * @param ReflectionClass $reflection
     * @return Factory
     */
    public function setReflection($reflection)
    {
        $this->reflection = $reflection;
        return $this;
    }

    private function createAnnotationFor($tag, $parsed)
    {
        $class = $this->getClassName($tag);
        $reflection = new ReflectionClass($class);
        if (!$reflection->implementsInterface("Slick\\Common\\AnnotationInterface")) {
            throw new InvalidAnnotationClassException(
                "$tag does not implement AnnotationInterface."
            );
        }

        return new $class($tag, $parsed);
    }

    private function getClassName($tag)
    {
        if (class_exists($tag)) {
            return $tag;
        }

        return $this->getDefaultClass();
    }

    private function getDefaultClass()
    {
        return self::$defaultClass;
    }

}