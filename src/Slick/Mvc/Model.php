<?php

/**
 * MVC Model
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Slick\Common\Inspector;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;
use Slick\Orm\Relation\BelongsTo;

/**
 * MVC Model
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Inspector\TagList[] $propertyList
 */
abstract class Model extends Entity implements EntityInterface
{

    /**
     * @read
     * @var Inspector
     */
    protected $_inspector;

    /**
     * @read
     * @var Inspector\TagList[]
     */
    protected $_propertyList = [];

    /**
     * @readwrite
     * @var string Name of the display field
     */
    protected $_displayField;

    /**
     * Returns the value of the provided column name
     *
     * @param string $name Column name
     *
     * @return mixed
     */
    public function getValue($name)
    {
        return $this->$name;
    }

    /**
     * Returns the primary key value
     *
     * @return integer
     */
    public function getKey()
    {
        $prmKey = $this->primaryKey;
        return $this->$prmKey;
    }

    /**
     * Returns editable data from this model
     * @return array
     */
    public function getData()
    {
        $data = [];
        $properties = $this->getPropertyList();
        foreach ($properties as $property => $meta) {
            $name = trim($property, '_');
            if ($meta->hasTag('@belongsto')) {
                /** @var BelongsTo $field */
                $fk = $this->getRelationsManager()
                    ->getRelation($property)
                    ->getRelated()->primaryKey;
                $data[$name] =  $this->$property->$fk;
            } else if ($meta->hasTag('@column')) {
                $data[$name] = $this->$property;
            }
        }
        return $data;
    }

    /**
     * Returns the list of editable properties of this model
     *
     * This list all the properties that have @column, @hasOne
     * or @belongsTo notations.
     *
     * @param boolean $external If its set to true it will return the
     * column names only
     *
     * @return Inspector\TagList[]
     */
    public function getPropertyList($external = false)
    {
        if (empty($this->_propertyList)) {
            $inspector = $this->_getInspector();

            foreach($inspector->getClassProperties() as $property) {
                $propertyData = $inspector->getPropertyMeta($property);
                if (
                    $propertyData->hasTag('@column') ||
                    $propertyData->hasTag('@hasone') ||
                    $propertyData->hasTag('@belongsto')
                ) {
                    $this->_propertyList[$property] = $propertyData;
                }
            }
        }
        if ($external) {
            $values = array_keys($this->_propertyList);
            return array_map(function($value){ return trim($value, '_');}, $values);
        }
        return $this->_propertyList;
    }

    /**
     * Returns the display field name
     *
     * The display field is used to print out the model instance name
     * when you request to print a model.
     *
     * For example:
     * model as the id, name, address fields, if you print out model with
     * echo $model, it will use the name field to print it or other field
     * if you define $_displayField property.
     *
     * @return string
     */
    public function getDisplayField()
    {
        if (is_null($this->_displayField)) {
            /** @var Inspector\TagList[] $properties */
            $properties = array_reverse($this->getPropertyList(), true);
            foreach ($properties as $name => $prop) {
                $name = trim($name, '_');
                if ($name == $this->primaryKey) {
                    continue;
                }

                if ($prop->hasTag('@display')) {
                    $this->_displayField = $name;
                    break;
                }
                $this->_displayField = $name;
            }
        }
        return $this->_displayField;

    }

    /**
     * Prints out this module text representation
     *
     * @return string
     */
    public function __toString()
    {
        $displayField = $this->getDisplayField();
        return $this->$displayField;
    }

    /**
     * Retrieves an array with primary keys and display fields
     *
     * This is used mainly for selected options
     *
     * @return array
     */
    public static function getList()
    {
        /** @var Model $model */
        $model = new static();
        $key = $model->primaryKey;
        $value = $model->getDisplayField();
        $list = static::all([
            'fields' => [$key, $value]
        ]);
        $result = [];
        foreach ($list as $inst) {
            $result[$inst->$key] = $inst->$value;
        }
        return $result;
    }

    public function getMultipleEntityRelations()
    {
        $result = [];
        foreach ($this->getRelationsManager()->relations as $key => $rel) {
            if (
                is_a(
                    $rel,
                    '\Slick\Orm\Relation\MultipleEntityRelationInterface'
                )
            ) {
                $result[trim($key, '_')] = $rel;
            }
        }
        return $result;
    }

    /**
     * Lazy load of model inspector
     *
     * @return Inspector
     */
    protected function _getInspector()
    {
        if (is_null($this->_inspector)) {
            $this->_inspector = new Inspector($this);
        }
        return $this->_inspector;
    }
} 