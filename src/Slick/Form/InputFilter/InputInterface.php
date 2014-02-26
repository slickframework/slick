<?php

/**
 * InputInterface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

use Slick\Filter\FilterChain;
use Slick\Validator\ValidatorChain;

/**
 * InputInterface
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface InputInterface
{

    /**
     * An input cannot be created without a name
     *
     * @param string $name Input name
     * @param array  $options
     */
    public function __construct($name, $options = array());

    /**
     * Sets the validation chain
     *
     * @param ValidatorChain $validators
     *
     * @return InputInterface
     */
    public function setValidatorChain(ValidatorChain $validators);

    /**
     * Returns the validation chain
     *
     * @return ValidatorChain
     */
    public function getValidatorChain();

    /**
     * Sets the filter chain
     *
     * @param FilterChain $filters
     *
     * @return InputInterface
     */
    public function setFilterChain(FilterChain $filters);

    /**
     * Returns the filter chain
     *
     * @return FilterChain
     */
    public function getFilterChain();

    /**
     * Returns true if and only if the values passes all chain validation
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Sets input value
     *
     * @param $value
     *
     * @return InputInterface
     */
    public function setValue($value);

    /**
     * Retrieves the input value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the error messages from last validation check
     *
     * @return array
     */
    public function getMessages();

    /**
     * Checks if this input is required
     * @return mixed
     */
    public function isRequired();

    /**
     * Checks if this input can be empty
     *
     * @return boolean
     */
    public function allowEmpty();

    /**
     * Returns the value without passing it thru the filter chain
     *
     * @return mixed
     */
    public function getRawValue();
} 