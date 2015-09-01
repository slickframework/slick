<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Object Definition Interface
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ObjectDefinitionInterface extends DefinitionInterface
{

    /**
     * Gets definition class name
     *
     * If class name is not set and there is an instance set the class name
     * will be retrieved from instance object.
     *
     * @return string
     */
    public function getClassName();

    /**
     * Gets the instance object for current definition
     *
     * If instance is not defined yet and the class name is set and
     * is an existing class, a new instance will be created and the
     * constructor arguments will be used.
     *
     * @return object
     */
    public function getInstance();

    /**
     * Sets constructor arguments used on instance instantiation
     *
     * @param array $arguments
     * @return $this|self
     */
    public function setConstructArgs(array $arguments);

    /**
     * Set a method to be called when resolving this definition
     *
     * @param string $name      Method name
     * @param array  $arguments Method parameters
     *
     * @return $this|self
     */
    public function setMethod($name, array $arguments = []);

    /**
     * Sets property value when resolving this definition
     *
     * @param string $name  The property name
     * @param mixed  $value The property value
     *
     * @return $this|self
     */
    public function setProperty($name, $value);

    /**
     * Gets property values
     *
     * @return array
     */
    public function getProperties();

    /**
     * Returns the list of methods to call
     *
     * @return mixed
     */
    public function getMethods();
}
