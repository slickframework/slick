<?php

/**
 * InputFilter
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

use Slick\Common\Base;
use Slick\Form\Exception\InvalidArgumentException;

/**
 * InputFilter
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InputFilter extends Base implements InputFilterInterface
{

    /**
     * @readwrite
     * @var Input[]|InputFilter[]
     */
    protected $_inputs;

    /**
     * @read
     * @var array An associative array with input names and
     * validation errors of all inputs that fail to validate
     * in the lasted call to InputFilter::isValid() method.
     */
    protected $_messages = [];

    /**
     * Add an input to the input filter
     *
     * If name is not set, the input name will be used to as the
     * name to retrieve it.
     *
     * @param Input|InputFilter $input
     * @param string $name (Optional) Name used to retrieve the input
     *
     * @throws \Slick\Form\Exception\InvalidArgumentException If input is
     *  an InputFilterInterface object and $name is not provided.
     *
     * @return InputFilterInterface
     */
    public function add($input, $name = null)
    {

        if (
            is_a($input, 'Slick\Form\InputFilter\InputFilterInterface') &&
            is_null($name)
        ) {
            throw new InvalidArgumentException(
                "You must set a name to add an input filter to the list of " .
                "inputs of an input filter."
            );
        }

        if (
            !is_a($input, 'Slick\Form\InputFilter\InputFilterInterface') &&
            !is_a($input, 'Slick\Form\InputFilter\InputInterface')
        ) {
            throw new InvalidArgumentException(
                "You can only add an input or input filter to an input filter."
            );
        }

        $key = $name;
        if (is_null($name)) {
            $key = $input->name;
        }

        $this->_inputs[$key] = $input;
        return $this;
    }

    /**
     * Retrieves the input stored with the provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return Input|InputFilter
     */
    public function get($name)
    {
        $input = null;
        if ($this->has($name)) {
            $input = $this->_inputs[$name];
        }
        return $input;
    }

    /**
     * Check if this input filter has an input with the given name
     *
     * @param string $name Name under witch input was stored
     *
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->_inputs[$name]);
    }

    /**
     * Removes the input that was stored with the provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return boolean True if an input with given name existed and was removed
     */
    public function remove($name)
    {
        $removed = false;
        if ($this->has($name)) {
            unset($this->_inputs[$name]);
            $removed = true;
        }
        return $removed;
    }

    /**
     * Populate data on current input list
     *
     * @param array $data An associative array with input names and
     * corespondent values
     *
     * @return InputFilterInterface
     */
    public function setData($data)
    {
        foreach ($data as $name => $value) {
            if ($this->has($name)) {
                $this->get($name)->setValue($value);
            }
        }
        return $this;
    }

    /**
     * Check if all data set is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        $valid = true;
        $messages = array();

        /** @var Input $input */
        foreach ($this->_inputs as $input) {
            if (!$input->isValid()) {
                $valid = false;
                $messages[$input->name] = $input->getMessages();
            }
        }
        $this->_messages = $messages;
        return $valid;
    }

    /**
     * Get filtered value of the input with provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return mixed
     */
    public function getValue($name)
    {
        $value = null;
        if ($this->has($name)) {
            $value = call_user_func_array(
                [$this->_inputs[$name], 'getValue'],
                [$name]
            );
        }
        return $value;
    }

    /**
     * Returns all input values filtered
     *
     * @return array An associative array with input names as keys and
     * filtered values as values
     */
    public function getValues()
    {
        $values = array();
        /** @var Input $input */
        foreach ($this->_inputs as $name => $input) {
            if (strlen($name) > 0) {
                $values[$name] = call_user_func_array(
                    [$input, 'getValue'],
                    [$name]
                );
            }
        }
        return $values;
    }

    /**
     * Get raw (unfiltered) value of the input with provided name
     *
     * @param string $name Name under witch input was stored
     *
     * @return mixed
     */
    public function getRawValue($name)
    {
        $value = null;
        if ($this->has($name)) {
            $value = call_user_func_array(
                [$this->_inputs[$name], 'getRawValue'],
                [$name]
            );
        }
        return $value;
    }

    /**
     * Get all the values from data set without filtering
     *
     * @return array An associative array with input names as keys and
     * corespondent raw values
     */
    public function getRawValues()
    {
        $values = array();
        foreach ($this->_inputs as $name => $input) {
            /** @var Input $input */
            $values[$name] = call_user_func_array(
                [$input, 'getRawValue'],
                [$name]
            );
        }
        return $values;
    }

    /**
     * Returns all error messages from last isValid call
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }
}