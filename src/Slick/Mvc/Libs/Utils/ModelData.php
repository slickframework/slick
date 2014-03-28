<?php

/**
 * Model meta data inspector
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Libs\Utils;

use Slick\Mvc\Model,
    Slick\Common\Base,
    Slick\Common\Inspector,
    Slick\Orm\Relation\MultipleEntityRelationInterface;
use Slick\Utility\Text;

/**
 * Model meta data inspector
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Inspector $inspector
 * @property Model model
 * @property string $modelClass
 */
class ModelData extends Base
{

    /**
     * @read
     * @var Inspector Model inspector
     */
    protected $_inspector;

    /**
     * @read
     * @var string|object Model object or class name
     */
    protected $_model;

    /**
     * @read
     * @var string Model full qualified class name
     */
    protected $_modelClass;

    /**
     * @read
     * @var Inspector\TagList[]
     */
    protected $_propertyList;

    /**
     * @read
     * @var string Field used to print this model as string
     */
    protected $_displayField;

    /**
     * Overrides parent constructor to set the inspected model
     *
     * @param String $model
     * @param array $options
     */
    public function __construct($model, $options = [])
    {
        parent::__construct($options);
        $this->_model = $model;
        $this->_modelClass = $model;
        if (is_object($model)) {
            $this->_modelClass = get_class($model);
        }
    }

    /**
     * Return model inspector object
     *
     * @return Inspector
     */
    public function getInspector()
    {
        if (is_null($this->_inspector)) {
            $this->_inspector = new Inspector($this->_model);
        }
        return $this->_inspector;
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
            $inspector = $this->getInspector();

            foreach($inspector->getClassProperties() as $property) {
                $propertyData = $inspector->getPropertyMeta($property);
                if (
                    $propertyData->hasTag('@column') ||
                    $propertyData->hasTag('@hasOne') ||
                    $propertyData->hasTag('@belongsTo')
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
                if ($name == $this->model->primaryKey) {
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
     * Returns the list of relations (hasMany and hasAndBelongsToMany)
     *
     * @return MultipleEntityRelationInterface[]
     */
    public function getMultipleEntityRelations()
    {
        $result = [];
        $relMan = $this->model->getRelationsManager();
        foreach ($relMan->relations as $key => $rel) {
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

    public function getPluralName()
    {
        return Text::plural($this->getSingularName());
    }

    public function getSingularName()
    {
        return strtolower(end(explode('\\', $this->modelClass)));
    }
} 