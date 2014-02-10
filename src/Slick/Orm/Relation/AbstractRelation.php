<?php
/**
 * AbstractRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Base;
use Slick\Common\Inspector\Tag;
use Slick\Orm\Entity;
use Slick\Orm\Exception;

/**
 * AbstractRelation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractRelation extends Base implements RelationInterface
{

    /**
     * @readwrite
     * @var Entity
     */
    protected $_entity;

    /**
     * @readwrite
     * @var string
     */
    protected $_foreignKey;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_dependent;

    /**
     * @readwrite
     * @var Entity
     */
    protected $_related;

    /**
     * @readwrite
     * @var string
     */
    protected $_propertyName;

    /**
     * @readwrite
     * @var int The result index
     */
    protected $_index = -1;

    /**
     * Returns parent entity for this relation
     *
     * @return Entity
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Sets parent entity
     *
     * @param Entity $entity
     *
     * @return AbstractRelation
     */
    public function setEntity(Entity $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * Sets relation foreign key name
     *
     * @param string $foreignKey Foreign key name
     *
     * @return AbstractRelation
     */
    public function setForeignKey($foreignKey)
    {
        $this->_foreignKey = $foreignKey;
        return $this;
    }

    /**
     * Set relation entity dependency
     *
     * @param boolean $dependent
     *
     * @return AbstractRelation
     */
    public function setDependent($dependent = true)
    {
        $this->_dependent = $dependent;
        return $this;
    }

    /**
     * Return relation dependency state
     *
     * @return boolean
     */
    public function isDependent()
    {
        return $this->_dependent;
    }

    /**
     * Sets the related entity
     *
     * @param string|Entity $related
     *
     * @return AbstractRelation
     */
    public function setRelated($related)
    {
        $this->_related = $related;
        return $this;
    }

    /**
     * Returns related entity
     *
     * @return Entity
     */
    public function getRelated()
    {
        if (!is_a($this->_related, 'Slick\Orm\Entity')) {
            $this->_related = self::_createEntity($this->_related);
        }
        return $this->_related;
    }

    /**
     * Creates an entity object from the provided class name
     *
     * @param string $className Entity class name
     *
     * @return Entity
     */
    protected static function _createEntity($className)
    {
        $related = new $className();
        return $related;
    }

    /**
     * Returns the property name that holds this relation
     *
     * @return string The parent property name for this relation
     */
    public function getPropertyName()
    {
        return $this->_propertyName;
    }

    /**
     * Sets the property name that holds this relation
     *
     * @param string $name Property name to set
     * @return AbstractRelation
     */
    public function setPropertyName($name)
    {
        $this->_propertyName = $name;
        return $this;
    }
}