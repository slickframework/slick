<?php
/**
 * RelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Relation;
use Slick\Common\Inspector\Tag;
use Slick\Database\RecordList;
use Slick\Orm\Entity;
use Slick\Orm\EntityInterface;

/**
 * RelationInterface
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface RelationInterface
{

    /**
     * Returns parent entity for this relation
     *
     * @return Entity
     */
    public function getEntity();

    /**
     * Sets parent entity
     *
     * @param Entity $entity
     *
     * @return RelationInterface
     */
    public function setEntity(Entity $entity);

    /**
     * Sets relation foreign key name
     *
     * @param string $foreignKey Foreign key name
     *
     * @return RelationInterface
     */
    public function setForeignKey($foreignKey);

    /**
     * Returns foreign key name
     *
     * @return string
     */
    public function getForeignKey();

    /**
     * Set relation entity dependency
     *
     * @param boolean $dependent
     *
     * @return RelationInterface
     */
    public function setDependent($dependent = true);

    /**
     * Return relation dependency state
     *
     * @return boolean
     */
    public function isDependent();

    /**
     * Sets the related entity
     *
     * @param string|Entity $related
     *
     * @return RelationInterface
     */
    public function setRelated($related);

    /**
     * Returns related entity
     *
     * @return Entity
     */
    public function getRelated();

    /**
     * Creates a relation from notation tag
     *
     * @param Tag $tag
     * @param Entity $entity
     * @param $property
     *
     * @return RelationInterface
     */
    public static function create(Tag $tag, Entity &$entity, $property);

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
     * @return RelationInterface
     */
    public function setPropertyName($name);

    /**
     * Lazy loading of relations callback method
     *
     * @param EntityInterface $entity
     *
     * @return Entity|RecordList
     */
    public function load(EntityInterface $entity);

}