<?php

/**
 * Abstract relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Common\Base;
use Slick\Di\Container;
use Slick\Di\Definition;
use Slick\Di\ContainerBuilder;
use Slick\Orm\Exception\InvalidArgumentException;
use Slick\Orm\RelationInterface;
use Slick\Common\Inspector\Annotation;

/**
 * Abstract relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string|array $conditions Extra load conditions.
 * @property bool $singleResult
 *
 * @method bool isSingleResult() Returns true if this relation returns
 * a single result
 * @method AbstractRelation setContainer(Container $container)
 * Sets dependency container
 * @method AbstractRelation setConditions($conditions) Sets an extra
 * conditions when retrieving the related records.
 * @method string|array getConditions() Return current load conditions
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
     * @var Entity
     */
    protected $_relatedEntity;

    /**
     * @readwrite
     * @var string
     */
    protected $_foreignKey;

    /**
     * @readwrite
     * @var string
     */
    protected $_propertyName;

    /**
     * @readwrite
     * @var bool
     */
    protected $_dependent = true;

    /**
     * @readwrite
     * @var Container
     */
    protected $_container;

    /**
     * @readwrite
     * @var string|array
     */
    protected $_conditions;

    /**
     * @readwrite
     * @var bool
     */
    protected $_singleResult = true;

    /**
     * Returns the entity that defines the relation
     *
     * @return Entity
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Sets the entity that defines the relation
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function setEntity(Entity $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * Returns the entity that is related with the one defining the relation
     *
     * @return string
     */
    public function getRelatedEntity()
    {
        return $this->_relatedEntity;
    }

    /**
     * Sets the entity that is related with the one defining the relation
     *
     * @param string $entity
     * @return self
     */
    public function setRelatedEntity($entity)
    {
        if (!class_exists($entity)) {
            throw new InvalidArgumentException(
                "The entity class '{$entity}' does not exists."
            );
        }
        $this->_relatedEntity = $entity;
        return $this;
    }

    /**
     * Sets the foreign key for this relation
     *
     * @param string $foreignKey
     * @return self
     */
    public function setForeignKey($foreignKey)
    {
        $this->_foreignKey = $foreignKey;
        return $this;
    }

    /**
     * Returns the foreign key field name for this relation
     *
     * @return string
     */
    public function getForeignKey()
    {
        if (is_null($this->_foreignKey)) {
            $this->setForeignKey($this->_guessForeignKey());
        }
        return $this->_foreignKey;
    }

    /**
     * Sets dependency on delete operations
     *
     * @param bool $dependent
     *
     * @return self
     */
    public function setDependent($dependent = true)
    {
        $this->_dependent = $dependent;
        return $this;
    }

    /**
     * Returns the dependency flag for this relation
     *
     * @return bool
     */
    public function isDependent()
    {
        return $this->_dependent;
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
     *
     * @return self
     */
    public function setPropertyName($name)
    {
        $this->_propertyName = $name;
        return $this;
    }

    /**
     * Tries to guess the foreign key for this relation
     *
     * @return string
     */
    abstract protected function _guessForeignKey();

    /**
     * Returns the dependency container
     *
     * @return Container
     */
    public function getContainer()
    {
        if (is_null($this->_container)) {
            $container = ContainerBuilder::buildContainer([
                'sharedEventManager' => Definition::object(
                        'Zend\EventManager\SharedEventManager'
                    )
            ]);
            $this->setContainer($container);
        }
        return $this->_container;
    }

    /**
     * Factory method to create a relation based on a column
     * (annotation) object
     *
     * @param Annotation $annotation
     * @param Entity $entity
     * @param string $property
     *
     * @return self
     */
    public static function create(
        Annotation $annotation, Entity $entity, $property)
    {
        $parameters = $annotation->getParameters();
        unset ($parameters['_raw']);

        $related = $annotation->getValue();
        if (!class_exists($related)) {
            $parentClass = $entity->getClassName();
            throw new InvalidArgumentException(
                "Unknown class defined in {$parentClass}::_{$property}. ".
                "Class {$related} does not exists."
            );
        }

        /** @var BelongsTo $relation */
        $relation = new static($parameters);
        $relation->setEntity($entity)->setPropertyName($property);

        $relation->setRelatedEntity($related);
        return $relation;
    }
}
