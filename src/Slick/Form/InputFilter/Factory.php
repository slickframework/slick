<?php

/**
 * Input filter Factory
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\InputFilter;

use Slick\Common\Base;
use Slick\Filter\StaticFilter;
use Slick\Form\Exception;
use Slick\Validator\StaticValidator;

/**
 * Input filter Factory
 *
 * @package   Slick\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Factory extends Base
{

    /**
     * @readwrite
     * @var InputFilter
     */
    protected $_inputFilter;

    /**
     * @readwrite
     * @var array Factory meta definition
     */
    protected $_definition;

    /**
     * @readwrite
     * @var array
     */
    protected $_inputProperties = [
        'required' => false,
        'allowEmpty' => true,
        'validatorChain' => null,
        'filterChain' => null
    ];

    /**
     * Creates an input filter with the provided definition
     *
     * @param array $definition
     *
     * @return InputFilter
     */
    public static function create(array $definition)
    {
        /** @var Factory $factory */
        $factory = new static(['definition' => $definition]);
        return $factory->newInputFilter();
    }

    /**
     * Creates a new input filter
     *
     * @param array $definition Factory meta definition
     *
     * @return InputFilter
     */
    public function newInputFilter(array $definition = array())
    {
        if (!empty($definition)) {
            $this->_definition = $definition;
        }
        $this->_inputFilter = new InputFilter();

        foreach ($this->_definition as $key => $item) {
            if (!is_string($key)) {
                $key = $item['name'];
            }
            $this->_addInput($item, $key);
        }

        return $this->_inputFilter;
    }

    /**
     * Creates an input
     *
     * @param array $data
     * @param null $name
     *
     * @return Input
     */
    public static function createInput(array $data, $name = null)
    {
        /** @var Factory $factory */
        $factory = new static();
        return $factory->_newInput($data, $name);
    }

    /**
     * Adds an input to the input filter
     *
     * @param array $data
     * @param null $name
     */
    protected function _addInput(array $data, $name = null)
    {
        $this->_inputFilter->add($this->_newInput($data, $name));
    }

    /**
     * Creates a new input
     *
     * @param array $data
     * @param null $name
     *
     * @return Input
     */
    protected function _newInput(array $data, $name = null)
    {
        $options = array();
        foreach (array_keys($this->_inputProperties) as $key) {
            if (isset($data[$key])) {
                $options[$key] = $data[$key];
            }
        }
        $input = new Input($name, $options);

        if (isset($data['filters'])) {
            $this->_addFilters($input, $data['filters']);
        }

        if (isset($data['validation'])) {
            $this->_addValidators($input, $data['validation']);
        }
        return $input;
    }

    /**
     * Add filters to an input
     *
     * @param Input $input
     * @param array $filters
     */
    protected function _addFilters(Input &$input, array $filters)
    {
        foreach($filters as $filter) {
            $input->getFilterChain()->add(StaticFilter::create($filter));
        }
    }

    /**
     * Add validators to an input
     *
     * @param Input $input
     * @param array $validators
     */
    protected function _addValidators(Input &$input, array $validators)
    {
        foreach($validators as $validator => $message) {
            $input->getValidatorChain()
                ->add(StaticValidator::create($validator, $message));
        }
    }
} 