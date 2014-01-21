<?php
/**
 * Element
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
 * Element
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Element extends Base implements ElementInterface
{

    /**
     * @readwrite
     * @var string Element name
     */
    protected $_name;

    /**
     * @readwrite
     * @var array Element attributes
     */
    protected $_attributes = array();

    /**
     * @readwrite
     * @var string Element label
     */
    protected $_label;

    /**
     * @readwrite
     * @var string The element value
     */
    protected $_value;

    /**
     * @readwrite
     * @var array Error messages
     */
    protected $_messages = array();

    /**
     * Sets element name
     *
     * @param string $name The name to set
     *
     * @return Element A self instance for method call chains
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Returns current element's name
     *
     * @return string Element's name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Add an attribute to the element
     *
     * HTML attribute added will use the $key param as name and will be set
     * to the $value value. Null values indicate that the attribute will
     * have its value equals to its name
     *
     * @param string $key The attribute key (name)
     * @param string $value The attribute value
     *
     * @return Element A self instance for method call chains
     */
    public function addAttribute($key, $value = null)
    {
        $this->_attributes[$key] = $value;
        return $this;
    }

    /**
     * Returns the value of the attribute with the give key
     *
     * @param string $key The attribute to search for
     *
     * @return string|null The attributes value of null if not exists
     */
    public function getAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            return $this->_attributes[$key];
        }
        return null;
    }

    /**
     * Checks if the given attribute is set to this element
     *
     * @param  string $key The attribute to search for
     *
     * @return boolean True if element has the attribute with the given name
     *  defined, ot false otherwise.
     */
    public function hasAttribute($key)
    {
        return isset($this->_attributes[$key]);
    }

    /**
     * Sets element's label
     *
     * @param string $label
     *
     * @return Element A self instance for method call chains
     */
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * Returns current element label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * Sets elements value
     *
     * @param string $value
     *
     * @return Element A self instance for method call chains
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Returns current element value
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }
}