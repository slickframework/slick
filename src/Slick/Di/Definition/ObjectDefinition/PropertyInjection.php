<?php

/**
 * PropertyInjection
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition\ObjectDefinition;

/**
 * Property definition to use in property injection
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class PropertyInjection
{

    /**
     * Property name
     * @var string
     */
    protected $_propertyName;

    /**
     * Property value
     * @var mixed
     */
    protected $_value;

    /**
     * Creates a property definition for property injection
     *
     * @param string $propertyName
     * @param mixed $value
     */
    public function __construct($propertyName, $value)
    {
        $this->_propertyName = $propertyName;
        $this->_value = $value;
    }

    /**
     * Returns property name
     *
     * @return string
     */
    public function getPropertyName()
    {
        return $this->_propertyName;
    }

    /**
     * Return property value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }


} 