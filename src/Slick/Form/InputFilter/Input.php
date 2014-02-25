<?php

/**
 * Input
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

use Slick\Common\Base;
use Slick\Filter\FilterChain;
use Slick\Validator\ValidatorChain;

/**
 * Input
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Input extends Base implements InputInterface
{

    /**
     * Sets the validation chain
     *
     * @param ValidatorChain $validator
     *
     * @return InputInterface
     */
    public function setValidatorChain(ValidatorChain $validator)
    {
        // TODO: Implement setValidatorChain() method.
    }

    /**
     * Returns the validation chain
     *
     * @return ValidatorChain
     */
    public function getValidatorChain()
    {
        // TODO: Implement getValidatorChain() method.
    }

    /**
     * Returns true if and only if the values passes all chain validation
     *
     * @return boolean
     */
    public function isValid()
    {
        // TODO: Implement isValid() method.
    }

    /**
     * Sets input value
     *
     * @param $value
     *
     * @return InputInterface
     */
    public function setValue($value)
    {
        // TODO: Implement setValue() method.
    }

    /**
     * Retrieves the input value
     *
     * @return mixed
     */
    public function getValue()
    {
        // TODO: Implement getValue() method.
    }

    /**
     * Returns the error messages from last validation check
     *
     * @return array
     */
    public function getMessages()
    {
        // TODO: Implement getMessages() method.
    }

    /**
     * Checks if this input is required
     * @return mixed
     */
    public function isRequired()
    {
        // TODO: Implement isRequired() method.
    }

    /**
     * Checks if this input can be empty
     *
     * @return boolean
     */
    public function allowEmpty()
    {
        // TODO: Implement allowEmpty() method.
    }

    /**
     * Sets the filter chain
     *
     * @param FilterChain $filters
     *
     * @return InputInterface
     */
    public function setFilterChain(FilterChain $filters)
    {
        // TODO: Implement setFilterChain() method.
    }

    /**
     * Returns the filter chain
     *
     * @return FilterChain
     */
    public function getFilterChain()
    {
        // TODO: Implement getFilterChain() method.
    }

    /**
     * Returns the value without passing it thru the filter chain
     *
     * @return mixed
     */
    public function getRawValue()
    {
        // TODO: Implement getRawValue() method.
    }
}