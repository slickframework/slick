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
     * Sets the validation chain
     *
     * @param ValidatorChain $validator
     *
     * @return InputInterface
     */
    public function setValidatorChain(ValidatorChain $validator);

    /**
     * Returns the validation chain
     *
     * @return ValidatorChain
     */
    public function getValidatorChain();

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
} 