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
use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Slick\Form\InputFilter\Factory as InputFilterFactory;
use Slick\Validator\StaticValidator;

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
        'text' => 'Slick\Form\Element\Text',
        'dateTime' => 'Slick\Form\Element\DateTime',
        'hidden' => 'Slick\Form\Element\Hidden',
        'select' => 'Slick\Form\Element\Select',
        'area' => 'Slick\Form\Element\Area',
        'checkbox' => 'Slick\Form\Element\Checkbox',
        'selectMultiple' => 'Slick\Form\Element\SelectMultiple',
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
        'value' => null,
        'options' => []
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
        $container = ContainerBuilder::buildContainer([
            $name => Definition::object('Slick\Form\Form')
                ->constructor([$name])
        ]);

        $this->_form = $container->get($name);

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

            if (isset($data['input'])) {
                $element->input = InputFilterFactory::createInput(
                    $data['input'],
                    $element->getName()
                );
            }

            if (!empty($data['validate'])) {
                $this->addValidation($element, $data['validate']);
            }

            foreach (array_keys($this->_elementProperties) as $key) {
                if (isset($data[$key])) {
                    $element->$key = $data[$key];
                }
            }

            $form->add($element);
        }
    }

    /**
     * Add validator to the provided element
     *
     * @param Element $element
     * @param array $data
     */
    public function addValidation(Element &$element, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $element->getInput()->getValidatorChain()->add(StaticValidator::create($key, $value));
                $this->checkRequired($key, $element);
            } else {
                $element->getInput()->getValidatorChain()->add(StaticValidator::create($value));
                $this->checkRequired($value, $element);
            }
        }
    }

    /**
     * Check required flag based on validator name
     *
     * @param $validator
     * @param Element $element
     */
    public function checkRequired($validator, Element &$element)
    {
        if (in_array($validator, ['notEmpty'])) {
            $element->getInput()->required = true;
            $element->getInput()->allowEmpty = false;
        }
    }
} 