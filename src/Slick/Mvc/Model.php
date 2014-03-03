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
            $inspector = new Inspector($this);

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
} 