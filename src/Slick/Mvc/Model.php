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
                $data[$name] =  $this->$property->fk;
            } else if ($meta->hasTag('@column')) {
                $data[$name] = $this->$property;
            }
        }
        return $data;
    }

    /**
     * Returns the list of editable properties of this model
     *
     * This list all the properties that have @column, @hasone
     * or @belongsto notations.
     *
     * @return Inspector\TagList[]
     */
    public function getPropertyList()
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
        return $this->_propertyList;
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