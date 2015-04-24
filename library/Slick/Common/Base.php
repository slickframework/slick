<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

/**
 * Base class that implements getters and setters using PHP magic methods
 * __get() __set() __is() and __call().
 *
 * Base class uses the PHP magic methods to handle class properties in a
 * way that is a lot easier to work with. It defines an annotation for property
 * visibility and sets the "Getters" and "Setters" for all of those properties.
 * It prevents the creation of new properties as it throws exceptions if
 * you try to assign a value to an undefined property.
 * It also sets a very flexible constructor that allows you to create objects
 * only with some properties defined by passing an array (with those values)
 * or an object as argument.
 * Slick framework uses it in almost every class so it is important that
 * you understand how it works and the benefits of using it.
 *
 * @package Slick\Common
 */
class Base
{
    /**
     * Trait with magic methods and handlers
     */
    use BaseMethods;

    /**
     * Constructor assign properties based on the array or object given.
     *
     * The constructor will use the array keys or the object property
     * names to set the same property values with the ones given.
     *
     * @param array|object $options The properties for the object
     *                              being constructed.
     */
    public function __construct($options = [])
    {
        $this->hydrate($options);
    }
}