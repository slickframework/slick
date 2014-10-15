<?php

/**
 * Form
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Scaffold;

use Slick\Common\Inspector;
use Slick\Form\Element\Submit;
use Slick\Orm\Relation\HasMany;
use Slick\Orm\Relation\HasOne;
use Slick\Mvc\Model\Descriptor;
use Slick\Orm\Annotation\Column;
use Slick\Orm\RelationInterface;
use Slick\Form\Form as SlickFrom;
use Slick\Orm\Relation\HasAndBelongsToMany;

/**
 * Form
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends SlickFrom
{

    /**
     * @read
     * @var Descriptor
     */
    protected $_descriptor;

    /**
     * @var Column[]
     */
    private $_columns = [];

    /**
     * @var RelationInterface[]
     */
    private $_relations = [];

    /**
     * @var array
     */
    private $_properties = [];

    /**
     * @var Inspector
     */
    private $_inspector;

    /**
     * @var array
     */
    private $_validations = [
        'notEmpty', 'email', 'url', 'number', 'alphaNumeric'
    ];

    /**
     * @var array
     */
    private $_filters = [
        'text', 'htmlEntities', 'number', 'url'
    ];

    /**
     * Add elements to the form based on the model notations
     *
     * @param string $name
     * @param Descriptor $descriptor
     */
    public function __construct($name, Descriptor $descriptor)
    {
        $this->_descriptor = $descriptor;
        $this->_columns = $this->_descriptor->getColumns();
        $this->_relations = $this->_descriptor->getRelations();
        $this->_inspector = new Inspector(
            $this->_descriptor->getDescriptor()->getEntity()
        );
        $this->_properties = $this->_inspector->getClassProperties();
        parent::__construct($name);
    }

    /**
     * Callback for form setup
     */
    protected function _setup()
    {
        foreach ($this->_properties as $property) {
            $element = $this->_createElement($property);
            if ($element) {
                $this->addElement($element['name'], $element);
            }
        }
        $this->add(
            new Submit(
                ['value' => 'Save']
            )
        );
    }

    protected function _createElement($property)
    {
        if (isset($this->_columns[$property])) {
            return $this->_createFromColumn(
                $property,
                $this->_columns[$property]
            );
        }
        if (isset($this->_relations[$property])) {
            return $this->_createFromRelation(
                $property,
                $this->_relations[$property]
            );
        }
        return false;
    }

    protected function _createFromColumn($property, Column $column)
    {
        $name = trim($property, '_');
        $options = [
            'name' => $name,
            'label' => ucfirst($name),
            'type' => 'text'
        ];
        $this->_addValidateOptions($property, $options);
        if ($column->getParameter('primaryKey')) {
            $options['type'] = 'hidden';
        }
        if ($column->getParameter('size') == 'big') {
            $options['type'] = 'area';
        }
        if ($column->getParameter('type') == 'boolean') {
            $options['type'] = 'checkbox';
        }
        if ($column->getParameter('type') == 'datetime') {
            $options['type'] = 'dateTime';
        }
        return $options;
    }

    protected function _createFromRelation(
        $property, RelationInterface $relation)
    {
        if (($relation instanceof HasOne) || ($relation instanceof HasMany)) {
            return false;
        }
        $name = trim($property, '_');
        $options = [
            'name' => $name,
            'label' => ucfirst($name),
            'type' => 'select'
        ];
        if ($relation instanceof HasAndBelongsToMany) {
            $options['type'] = 'selectMultiple';
        }
        $this->_addValidateOptions($property, $options);
        $optionsList = call_user_func(
            [$relation->getRelatedEntity(), 'getList']
        );
        $options['options'] = $optionsList;

        return $options;
    }

    protected function _addValidateOptions($property, array &$options)
    {
        $metaData = $this->_inspector->getPropertyAnnotations($property);
        if ($metaData->hasAnnotation('@validate')) {
            /** @var Inspector\Annotation $annotation */
            $annotation = $metaData->getAnnotation('@validate');
            $validators = $annotation->allValues();
            foreach ($validators as $validator) {
                if (in_array($validator, $this->_validations)) {
                    $options['validate'][] = $validator;
                }
            }
        }
    }
}
