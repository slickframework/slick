<?php

/**
 * Form Factory
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Slick\Common\Base;
use Slick\Form\InputFilter\Factory as InputFilterFactory;

/**
 * Form Factory
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Factory extends Base
{

    /**
     * @var array A list of form element classes and its alias
     */
    protected static $_elementAlias = [
        'text' => 'Slick\Form\Element\Text'
    ];

    /**
     * @readwrite
     * @var Form
     */
    protected $_form;

    /**
     * @readwrite
     * @var array A list of default element properties
     */
    protected $_elementProperties = [
        'label' => null,
        'attributes' => [],
        'value' => null
    ];

    /**
     * Creates a form from provided definition
     *
     * @param string $name
     * @param array $definition
     *
     * @return Form
     */
    public static function create($name, array $definition)
    {
        /** @var Factory $factory */
        $factory = new static();
        return $factory->newForm($name, $definition);
    }

    /**
     * Creates a new form object
     *
     * @param string $name
     * @param array $definition
     *
     * @return Form
     */
    public function newForm($name, array $definition)
    {
        $this->_form = new Form($name);
        foreach ($definition as $name => $element) {
            $this->addElement($this->_form, $name, $element);
        }
        return $this->_form;
    }

    /**
     * Adds an element to the form
     *
     * @param FieldsetInterface $form
     * @param string            $name
     * @param array             $data
     *
     * @throws Exception\UnknownElementException
     */
    public function addElement(FieldsetInterface &$form, $name, $data)
    {
        if ($data['type'] == 'fieldset') {
            $fieldset = new Fieldset(['name' => $name]);
            foreach ($data['elements'] as $key => $def) {
                $this->addElement($fieldset, $key, $def);
            }
            $form->add($fieldset);
        } else {
            if (array_key_exists($data['type'], static::$_elementAlias)) {
                $class = static::$_elementAlias[$data['type']];
            } else if (
                is_subclass_of($data['type'], 'Slick\Form\ElementInterface')
            ) {
                $class = $data['type'];
            } else {
                throw new Exception\UnknownElementException(
                    "'{$data['type']}' is not a known alias or an implementation of " .
                    "Slick\\Form\\ElementInterface interface"
                );
            }

            /** @var Element $element */
            $element = new $class(['name' => $name]);
            foreach (array_keys($this->_elementProperties) as $key) {
                if (isset($data[$key])) {
                    $element->$key = $data[$key];
                }
            }

            if (isset($data['input'])) {
                $element->input = InputFilterFactory::createInput(
                    $data['input'],
                    $element->getName()
                );
            }

            $form->add($element);
        }
    }
} 