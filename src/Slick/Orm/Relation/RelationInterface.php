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
use Slick\Orm\Entity;

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
     * @param Tag    $tag
     * @param Entity $entity
     *
     * @return RelationInterface
     */
    public static function create(Tag $tag, Entity &$entity);

}