<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use Doctrine\Common\Annotations\PhpParser;
use ReflectionClass;
use Slick\Common\AnnotationInterface;
use Slick\Common\Exception\InvalidAnnotationClassException;

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
     * @var PhpParser
     */
    private $phpParser;

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
     * @return Factory
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

    /**
     * Create the annotation with the provided name and parsed value
     *
     * @param string $tag    The tag name
     * @param mixed  $parsed The metadata nest to tag name
     *
     * @return AnnotationInterface
     */
    private function createAnnotationFor($tag, $parsed)
    {
        $class = $this->getClassName($tag);
        $reflection = new ReflectionClass($class);
        if (
            !$reflection->implementsInterface(
                "Slick\\Common\\AnnotationInterface"
            )
        ) {
            throw new InvalidAnnotationClassException(
                "$tag does not implement AnnotationInterface."
            );
        }

        return new $class($tag, $parsed);
    }

    /**
     * Returns class name for given tag name
     *
     * If the tag in not a FQN class the it will pass control to the
     * Factory::getClassAlasName().
     *
     * @param string $tag The tag name
     *
     * @return string
     */
    private function getClassName($tag)
    {
        $className = $tag;
        if (!class_exists($tag)) {
            $className = $this->getClassInNamespace($tag);
        }
        return $className;
    }

    /**
     * Check containing class namespace for annotation class with
     * given tag name
     *
     * @param string $tag The tag name
     *
     * @return string
     */
    private function getClassInNamespace($tag)
    {
        $namespace = $this->reflection->getNamespaceName();
        $className = "{$namespace}\\{$tag}";

        return class_exists($className)
            ? $className
            : $this->getClassAliasName($tag);
    }

    /**
     * Check namespaces and alias for the class with the given tag name
     *
     * @param string $tag The tag name
     *
     * @return string
     */
    private function getClassAliasName($tag)
    {
        $imports = $this->getPhpParser()
            ->parseClass($this->reflection);
        $class = $this->getDefaultClass();

        foreach ($imports as $alias => $namespace) {
            $regExp = "/($alias){1}/i";
            $name = preg_replace($regExp, $namespace, $tag);

            if (class_exists($name)) {
                $class = $name;
                break;
            }
        }

        return $class;
    }

    /**
     * Returns the static default annotation class name
     *
     * @return string
     */
    private function getDefaultClass()
    {
        return self::$defaultClass;
    }

    /**
     * Returns the parser for PHP file contents
     *
     * @return PhpParser
     */
    private function getPhpParser()
    {
        if (is_null($this->phpParser)) {
            $this->phpParser = new PhpParser();
        }
        return $this->phpParser;
    }

}