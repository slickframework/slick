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
     * Returns the elements name
     *
     * @return string
     */
    public function getName();

    /**
     * Set elements's name
     *
     * @param string $name
     *
     * @return ElementInterface
     */
    public function setName($name);

    /**
     * Set an attribute for this element
     *
     * @param string $key Attributes name
     * @param string $value Attribute value
     *
     * @return ElementInterface
     */
    public function setAttribute($key, $value);

    /**
     * Sets all attributes of this element
     *
     * @param array $attributes
     *
     * @return ElementInterface
     */
    public function setAttributes(array $attributes);

    /**
     * Check if this element has an attribute with the provided name
     *
     * @param string $name
     *
     * @return boolean True of elements has an attribute with the provided name
     *  false otherwise
     */
    public function hasAttribute($name);

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
    public function getAttribute($key, $default = null);

    /**
     * Returns the element's list of attributes
     *
     * @return array The list of attributes
     */
    public function getAttributes();

    /**
     * Returns the element value
     *
     * @return string
     */
    public function getValue();

    /**
     * Sets element default value
     *
     * @param string $value
     *
     * @return ElementInterface
     */
    public function setValue($value);

    /**
     * Set element label
     *
     * @param string $label
     *
     * @return ElementInterface
     */
    public function setLabel($label);

    /**
     * Returns current label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns current error messages
     *
     * @return array
     */
    public function getMessages();

    /**
     * Renders the form as HTML string
     *
     * @return string The HTML output string
     */
    public function render();

    /**
     * Sets template decorator for this element
     *
     * @param TemplateInterface $template
     *
     * @return ElementInterface
     */
    public function setTemplate(TemplateInterface $template);
} 