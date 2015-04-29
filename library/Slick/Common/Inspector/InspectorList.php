<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Inspector;

use Slick\Common\Exception\InvalidArgumentException;
use Slick\Common\Inspector;
use Slick\Common\Utils\ArrayObject;

/**
 * Holds current session class inspector object to be reused
 *
 * This pool is for performance proposes and it should always be used to
 * create/reuse a class inspector.
 *
 * @package Slick\Common\Inspector
 */
final class InspectorList
{
    /**
     * @var ArrayObject
     */
    private $inspectors;

    /**
     * @var InspectorList
     */
    private static $instance;

    /**
     * @param String|Object $class The class name or object to inspect.
     *
     * @return mixed
     *
     * @throws \Slick\Common\Exception\InvalidArgumentException If the class
     *  has no inspector in the pool
     */
    public function get($class)
    {
        if (!$this->has($class)) {
            throw new InvalidArgumentException(
                "Trying to retrieve an non-existent inspector."
            );
        }

        return $this->inspectors->offsetGet($this->className($class));
    }

    /**
     * Adds an inspector to the list
     *
     * @param Inspector $inspector
     */
    public function add(Inspector $inspector)
    {
        $name = $this->className($inspector->getClass());
        $this->inspectors[$name] = $inspector;
    }

    /**
     * Check if an inspector for provided class already exists
     *
     * @param String|Object $class The class name or object to inspect.
     *
     * @return bool True if object exists, false otherwise
     */
    public function has($class)
    {
        return $this->inspectors->offsetExists($this->className($class));
    }

    /**
     * Gets current instance of the definition pool
     *
     * @return InspectorList
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Gets the class name for provided object
     *
     * @param string|object $object
     *
     * @return string|object
     */
    private function className($object)
    {
        $className = $object;
        if (is_object($object)) {
            $className = get_class($object);
        }
        return $className;
    }

    /**
     * Private constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    private function __construct()
    {
        $this->inspectors = new ArrayObject();
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function __wakeup()
    {
    }
}