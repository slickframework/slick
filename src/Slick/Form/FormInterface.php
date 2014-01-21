<?php

/**
 * FormInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * FormInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FormInterface extends FieldsetInterface
{
    /**
     * Adds a fieldset to the list of form's fielsets
     * 
     * @param FieldsetInterface $fieldset FieldsetInterface object to add
     *
     * @return FormInterface A self instance for method call chains
     */
    public function addFieldset(FieldsetInterface $fieldset);

    /**
     * Sets the form action
     * 
     * @param string $string The URL/URI string for form action
     *
     * @return FormInterface A self instance for method call chains
     */
    public function setAction($string);

    /**
     * Sets the list of fieldsets of this form
     * 
     * @param FieldsetListInterface $fieldsetList Fieldset list to set
     *
     * @return FormInterface A self instance for method call chains
     */
    public function setFieldsets(FieldsetListInterface $fieldsetList);

    /**
     * Returns the form's fieldset list
     * 
     * @return FieldsetListInterface Form's fieldset list
     */
    public function getFieldsets();
}