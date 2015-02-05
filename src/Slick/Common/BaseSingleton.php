<?php

/**
 * BaseSingleton
 * 
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common;

/**
 * Base singleton variation
 * 
 * Base class uses the PHP magic methods to handle class properties in a
 * way that is a lot easier to work with. It defines an annotation for property
 * visibility and sets the "Getters" and "Setters" for all of this properties.
 * It prevents the creation of new properties as it throws exceptions if
 * you try to assign a value to an undefined property.
 * It also sets a very flexible constructor that allows you to create objects
 * only with some properties defined by passing an array (with those values)
 * or an object as argument.
 * Slick framework uses it in almost every class so it is important that
 * you understand how it works and the benefits of using it.
 *
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class BaseSingleton implements SingletonInterface
{
    /**
     * Trait with method for base class
     */
    use BaseMethods;

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     * 
     * @param array $options A list of properties for this connector
     */
    protected function __construct($options = [])
    {
        $this->_createObject($options);
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * @return void
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
     * @return void
     */
    private function __wakeup()
    {
    }
}