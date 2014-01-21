<?php

/**
 * FieldsetInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * FieldsetInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FieldsetInterface extends ElementInterface
{

    /**
     * Add an element to the fielset element
     * 
     * @param ElementInterface $element The element object to add
     *
     * @return FieldsetInterface A self instance for method call chains
     */
    public function addElement(ElementInterface $element);

    /**
     * Sets the list of the element for this field set
     * 
     * @param ElementListInterface $elements Elements list for this fieldset
     */
    public function setElements(ElementListInterface $elements);

    /**
     * Returns the list of elements from this fieldset
     * 
     * @return ElementListInterface List of ElementInterface objects
     */
    public function getelements();
}