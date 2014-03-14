<?php

/**
 * Form
 *
 * @package   Slick\Mvc\Scaffold
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Scaffold;

use Slick\Common\Inspector\TagList,
    Slick\Form\Form as SlickFrom,
    Slick\Form\Element,
    Slick\Orm\Entity\Column,
    Slick\Mvc\Model,
    Slick\Validator\StaticValidator,
    Slick\Filter\StaticFilter;

/**
 * Form
 *
 * @package   Slick\Mvc\Scaffold
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends SlickFrom
{

    /**
     * @readwrite
     * @var Model
     */
    protected $_model;

    protected $_validations = [
        'notEmpty', 'email', 'url', 'number', 'alphaNumeric'
    ];

    /**
     * Add elements to the form based on the model notations
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options = array())
    {
        parent::__construct($name, $options);
        foreach($this->getModel()->getPropertyList() as $propertyName => $property) {
            $element = $this->_createElement($propertyName, $property);
            if ($element) {
                $this->add($element);
            }
        }
        $this->add(
            new Element\Submit(
                ['value' => 'Save']
            )
        );
    }

    /**
     * Lazy loads the model object
     *
     * @return Model
     */
    public function getModel()
    {
        if (is_string($this->_model)) {
            $class = $this->_model;
            $this->_model = new $class;
        }
        return $this->_model;
    }

    /**
     * Creates an element based on the notations of a property
     *
     * @param string $name
     * @param TagList $property
     *
     * @return false|Element
     */
    protected function _createElement($name, TagList $property)
    {
        if ($property->hasTag('@belongsto')) {
            $element = $this->_createFromRelation($name);
        } else {
            $element = $this->_createFromColumn($name, $property);
        }

        if ($property->hasTag('@validate')) {
            $validations = [];
            $tag = $property->getTag('@validate');
            $validations[] = $tag->value;

            if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
                $validations = $tag->value->getArrayCopy();
            }

            foreach ($validations as $validation)
            {
                if (in_array($validation, $this->_validations)) {
                    $element->getInput()->getValidatorChain()
                        ->add(StaticValidator::create($validation));
                }

                if ($validation == 'notEmpty') {
                    $element->getInput()->required = true;
                    $element->getInput()->allowEmpty = false;
                }
            }
        }

        if ($property->hasTag('@filter')) {
            $filters = [];
            $tag = $property->getTag('@filter');
            $filters[] = $tag->value;

            if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
                $filters = $tag->value->getArrayCopy();
            }

            foreach ($filters as $filter){
                $element->getInput()->getFilterChain()
                    ->add(StaticFilter::create($filter));
            }
        }


        return $element;
    }

    /**
     * Creates a form element from column object
     * @param string  $name
     * @param TagList $property
     *
     * @return Element
     */
    protected function _createFromColumn($name, TagList $property)
    {
        $column = Column::parse($property, $name);

        // Define types
        $type = 'text';
        if ($column->primaryKey) {
            $type = 'hidden';
        }

        if ($column->type == "datetime") {
            $type = 'datetime';
        }

        return $this->_element($type, $column->name);
    }

    /**
     * Factory method for elements
     *
     * @param string $type
     * @param string $name
     *
     * @return Element
     */
    protected function _element($type, $name)
    {
        switch ($type) {
            case 'hidden':
                $class = 'Slick\Form\Element\Hidden';
                break;

            case 'datetime':
                $class = 'Slick\Form\Element\DateTime';
                break;

            case 'select':
                $class = 'Slick\Form\Element\Select';
                break;

            case 'text':
            default:
                $class = 'Slick\Form\Element\Text';
        }

        return new $class(
            [
                'name' => $name,
                'label' => ucfirst($name)
            ]
        );
    }

    /**
     * Create form element from relation
     *
     * @param string $name
     *
     * @return Element
     */
    protected function _createFromRelation($name)
    {
        $relation = $this->getModel()->getRelationsManager()
            ->getRelation($name);

        /** @var Element\Select $element */
        $element = $this->_element('select', trim($name, '_'));
        $modelClass = get_class($relation->getRelated());
        $element->options = call_user_func([$modelClass, 'getList']);
        return $element;

    }

} 