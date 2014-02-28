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
use Slick\Form\InputFilter\InputFilter;

/**
 * FormInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface FormInterface extends FieldsetInterface
{

    /**
     * Set data to validate and/or populate elements
     *
     * @param  array $data
     *
     * @return FormInterface
     */
    public function setData($data);

    /**
     * Sets form input filter
     *
     * @param InputFilter $inputFilter
     *
     * @return FormInterface
     */
    public function setInputFilter(InputFilter $inputFilter);

    /**
     * lazy loads this for input filter
     *
     * @return InputFilter
     */
    public function getInputFilter();

    /**
     * Checks whenever the data set is valid or not
     *
     * This means that all the elements must be valid for this method
     * return boolean true, otherwise will always return false.
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Returns the error messages from last call to isValid method
     *
     * @return array
     */
    public function getMessages();

    /**
     * Returns all input values filtered
     *
     * @return array An associative array with input names as keys and
     * filtered values as values
     */
    public function getValues();
} 