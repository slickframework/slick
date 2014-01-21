<?php

/**
 * ElementInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * ElementInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ElementInterface
{
    
    /**
     * Sets element name
     * 
     * @param string $name The name to set
     * 
     * @return ElementInterface A self instance for method call chains
     */
    public function setName($name);

    /**
     * Returns current element's name
     * 
     * @return string Element's name
     */
    public function getName();

    /**
     * Add an attribute to the element
     *
     * HTML attribute added will use the $key param as name and will be set
     * to the $value value. Null values indicate that the attribute will
     * have its value equals to its name
     * 
     * @param string $key   The attribute key (name)
     * @param string $value The attribute value
     * 
     * @return @return ElementInterface A self instance for method call chains
     */
    public function addAttribute($key, $value = null);

    /**
     * Returns the value of the attribute with the give key
     * 
     * @param string $key The attribute to search for
     * 
     * @return string|null The attributes value of null if not exists
     */
    public function getAttribute($key);

    /**
     * Checks if the given attribute is set to this element
     * 
     * @param  string $key The attribute to search for
     * 
     * @return boolean True if element has the attribute with the given name
     *  defined, ot false otherwise.
     */
    public function hasAttribute($key);

    /**
     * Sets element's label
     *
     * @param string $label
     *
     * @return ElementInterface A self instance for method call chains
     */
    public function setLabel($label);

    /**
     * Returns current element label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Sets elements value
     *
     * @param string $value
     *
     * @return ElementInterface A self instance for method call chains
     */
    public function setValue($value);

    /**
     * Returns current element value
     * @return string
     */
    public function getValue();
}