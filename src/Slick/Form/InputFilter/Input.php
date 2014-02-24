<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 2/24/14
 * Time: 6:56 PM
 */

namespace Slick\Form\InputFilter;


use Slick\Common\Base;
use Slick\Validator\ValidatorChain;

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
}