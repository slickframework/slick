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
 *
 * @property string  $name
 * @property boolean $required
 * @property boolean $allowEmpty
 */
class Input extends Base implements InputInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var ValidatorChain
     */
    protected $_validatorChain;

    /**
     * @readwrite
     * @var FilterChain
     */
    protected $_filterChain;

    /**
     * @readwrite
     * @var mixed Value to filtered and validated
     */
    protected $_value;

    /**
     * @read
     * @var mixed Filtered value
     */
    protected $_filtered;

    /**
     * @read
     * @var array Validation errors
     */
    protected $_messages = [];

    /**
     * @readwrite
     * @var bool Required input flag
     */
    protected $_required = false;

    /**
     * @readwrite
     * @var bool Allow empty value flag
     */
    protected $_allowEmpty = true;

    /**
     * An input cannot be created without a name
     *
     * @param string $name Input name
     * @param array  $options
     */
    public function __construct($name, $options = array())
    {
        parent::__construct($options);
        $this->_name = $name;
    }

    /**
     * Sets the validation chain
     *
     * @param ValidatorChain $validator
     *
     * @return InputInterface
     */
    public function setValidatorChain(ValidatorChain $validator)
    {
        $this->_validatorChain = $validator;
        return $this;
    }

    /**
     * Returns the validation chain
     *
     * @return ValidatorChain
     */
    public function getValidatorChain()
    {
        if (is_null($this->_validatorChain)) {
            $this->_validatorChain = new ValidatorChain();
        }
        return $this->_validatorChain;
    }

    /**
     * Returns true if and only if the values passes all chain validation
     *
     * @return boolean
     */
    public function isValid()
    {
        $filtered = $this->getValue();
        $valid = $this->getValidatorChain()->isValid($filtered);
        $this->_messages = $this->getValidatorChain()->getMessages();
        return $valid;
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
        $this->_value = $value;
        $this->_filtered = null;
        return $this;
    }

    /**
     * Retrieves the input value
     *
     * @return mixed
     */
    public function getValue()
    {
        if (is_null($this->_filtered)) {
            $this->_filtered = $this->getFilterChain()->filter($this->_value);
        }
        return $this->_filtered;
    }

    /**
     * Returns the error messages from last validation check
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     * Checks if this input is required
     * @return mixed
     */
    public function isRequired()
    {
        return $this->_required;
    }

    /**
     * Checks if this input can be empty
     *
     * @return boolean
     */
    public function allowEmpty()
    {
        return $this->_allowEmpty;
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
        $this->_filterChain = $filters;
        return $this;
    }

    /**
     * Returns the filter chain
     *
     * @return FilterChain
     */
    public function getFilterChain()
    {
        if (is_null($this->_filterChain)) {
            $this->_filterChain = new FilterChain();
        }
        return $this->_filterChain;
    }

    /**
     * Returns the value without passing it thru the filter chain
     *
     * @return mixed
     */
    public function getRawValue()
    {
        return $this->_value;
    }
}