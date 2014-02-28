<?php

/**
 * AbstractElement
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Slick\Common\Base;

/**
 * AbstractElement
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractElement extends Base
{
    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var string
     */
    protected $_label;

    /**
     * @readwrite
     * @var array
     */
    protected $_attributes = [];

    /**
     * @readwrite
     * @var array
     */
    protected $_messages = [];

    /**
     * @readwrite
     * @var string
     */
    protected $_value;

    /**
     * Sets all attributes of this element
     *
     * @param array $attributes
     *
     * @return AbstractElement
     */
    public function setAttributes(array $attributes)
    {
        $this->_attributes = $attributes;
        return $this;
    }

    /**
     * Returns the element's list of attributes
     *
     * @return array The list of attributes
     */
    public function getAttributes()
    {
        return $this->_attributes;
    }

    /**
     * Returns the elements name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set elements's name
     *
     * @param string $name
     *
     * @return AbstractElement
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Set an attribute for this element
     *
     * @param string $key Attributes name
     * @param string $value Attribute value
     *
     * @return AbstractElement
     */
    public function setAttribute($key, $value)
    {
        $this->_attributes[$key] = $value;
        return $this;
    }

    /**
     * Check if this element has an attribute with the provided name
     *
     * @param string $name
     *
     * @return boolean True of elements has an attribute with the provided name
     *  false otherwise
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]);
    }

    /**
     * Gets the value of an attribute with the provided name.
     *
     * If there is no attribute with the given name the default value
     * is returned instead.
     *
     * @param string $key
     * @param string $default
     *
     * @return string|mixed
     */
    public function getAttribute($key, $default = null)
    {
        if ($this->hasAttribute($key)) {
            $default = $this->_attributes[$key];
        }
        return $default;
    }

    /**
     * Returns the element value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Sets element default value
     *
     * @param string $value
     *
     * @return AbstractElement
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Set element label
     *
     * @param string $label
     *
     * @return AbstractElement
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * Returns current label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }
} 