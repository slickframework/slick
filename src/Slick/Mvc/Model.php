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
use Slick\Mvc\Libs\Utils\ModelData;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;
use Slick\Orm\Relation\BelongsTo;

/**
 * MVC Model
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ModelData $modelData
 */
abstract class Model extends Entity implements EntityInterface
{

    /**
     * @readwrite
     * @var string Name of the display field
     */
    protected $_displayField;

    /**
     * @read
     * @var ModelData
     */
    protected $_modelData;

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
        $properties = $this->modelData->getPropertyList();
        foreach ($properties as $property => $meta) {
            $name = trim($property, '_');
            if ($meta->hasTag('@belongsTo')) {
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
     * Return metadata about this model
     *
     * @return ModelData
     */
    public function getModelData()
    {
        if (is_null($this->_modelData)) {
            $this->_modelData = new ModelData($this);
        }
        return $this->_modelData;
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
        return $this->modelData->getDisplayField();
    }

    /**
     * Prints out this module text representation
     *
     * @return string
     */
    public function __toString()
    {
        $displayField = $this->getDisplayField();
        return (String) $this->$displayField;
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

} 