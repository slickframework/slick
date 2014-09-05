<?php

/**
 * ORM Relation interface
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm;

use Slick\Common\Inspector\Annotation;
use Slick\Database\RecordList;
use Slick\Orm\Annotation\Column;

/**
 * Defines a relation between two entities
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface RelationInterface
{
    /**
     * Returns the entity that defines the relation
     *
     * @return Entity
     */
    public function getEntity();

    /**
     * Sets the entity that defines the relation
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function setEntity(Entity $entity);

    /**
     * Returns the entity that is related with the one defining the relation
     *
     * @return string
     */
    public function getRelatedEntity();

    /**
     * Sets the entity that is related with the one defining the relation
     *
     * @param string $entity
     * @return self
     */
    public function setRelatedEntity($entity);

    /**
     * Sets the foreign key for this relation
     *
     * @param string $foreignKey
     * @return self
     */
    public function setForeignKey($foreignKey);

    /**
     * Returns the foreign key field name for this relation
     *
     * @return string
     */
    public function getForeignKey();

    /**
     * Sets dependency on delete operations
     *
     * @param bool $dependent
     *
     * @return self
     */
    public function setDependent($dependent = true);

    /**
     * Returns the dependency flag for this relation
     *
     * @return bool
     */
    public function isDependent();

    /**
     * Returns the property name that holds this relation
     *
     * @return string The parent property name for this relation
     */
    public function getPropertyName();

    /**
     * Sets the property name that holds this relation
     *
     * @param string $name Property name to set
     *
     * @return self
     */
    public function setPropertyName($name);

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
        Annotation $annotation, Entity $entity, $property);

    /**
     * Lazy loading of relations callback method
     *
     * @param Entity $entity
     * @return Entity|RecordList
     */
    public function load(Entity $entity);
}
