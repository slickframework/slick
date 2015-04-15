<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

use ReflectionClass;
use Slick\Common\Annotation\Factory;
use Slick\Common\Annotation\Parser;
use Slick\Common\Annotation\AnnotationList;
use Slick\Common\Inspector\InspectorList;

/**
 * Inspector uses PHP reflection to inspect classes or objects.
 *
 * Used to store all information about a class including, properties,
 * methods, class annotations, property annotations and method annotations.
 *
 * @package Slick\Common
 */
class Inspector
{

    /**
     * @var string|object The object or class name that will be inspected
     */
    private $class;

    /**
     * @var array
     */
    private $annotations = [
        'class' => null,
        'properties' => [],
        'methods' => []
    ];

    /**
     * @var ReflectionClass
     */
    private $reflection = null;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * Constructs an inspector for a given class.
     *
     * @param String|Object $class The class name or object to inspect.
     */
    private function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Returns an inspector for provided class
     *
     * @param String|Object $class The class name or object to inspect.
     *
     * @return Inspector A new or reused inspector for provided class.
     */
    public static function forClass($class)
    {
        if (!InspectorList::getInstance()->has($class)) {
            $inspector = new Inspector($class);
            InspectorList::getInstance()->add($inspector);
        }
        return InspectorList::getInstance()->get($class);
    }

    /**
     * Retrieves the list of annotations from inspected class
     *
     * @return AnnotationList
     */
    public function getClassAnnotations()
    {
        if (is_null($this->annotations['class'])) {
            $comment = $this->getReflection()->getDocComment();
            $this->annotations['class'] = $this->getFactory()
                ->getAnnotationsFor($comment);
        }
        return $this->annotations['class'];
    }


    /**
     * Returns the class bind to this inspector
     *
     * @return object|string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Return current class reflection object
     *
     * @return ReflectionClass
     */
    private function getReflection()
    {
        if (is_null($this->reflection)) {
            $this->reflection = new ReflectionClass($this->getClass());
        }
        return $this->reflection;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        if (is_null($this->factory)) {
            $factory = new Factory();
            $factory->setReflection($this->getReflection());
            $this->setFactory($factory);
        }
        return $this->factory;
    }

    /**
     * @param Factory $factory
     *
     * @return Inspector
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }


}