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
     * @param Entity $related
     *
     * @return AbstractRelation
     */
    public function setRelated(Entity $related)
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
        return $this->_related;
    }

    /**
     * Creates an entity object from the provided class name
     *
     * @param string $className Entity class name
     *
     * @return Entity
     *
     * @throws \Slick\Orm\Exception\UndefinedClassException if the class does
     *  not exists
     * @throws \Slick\Orm\Exception\InvalidArgumentException if the class
     *  does not implement Slick\Orm\EntityInterface interface
     */
    protected static function _createEntity($className)
    {
        if (!class_exists($className)) {
            throw new Exception\UndefinedClassException(
                "The class {$className} is not defined"
            );
        }

        $related = new $className();

        if (!is_a($related, 'Slick\Orm\EntityInterface')) {
            throw new Exception\InvalidArgumentException(
                "The class {$className} does not implement " .
                "Slick\\Orm\\EntityInterface"
            );
        }

        return $related;
    }
}