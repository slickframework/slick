<?php

/**
 * Has many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Database\Sql;
use Slick\Di\Definition;
use Slick\Orm\Sql\Select;
use Slick\Orm\Events\Delete;
use Slick\Database\RecordList;
use Slick\Orm\RelationInterface;
use Slick\Common\Inspector\Annotation;
use Zend\EventManager\SharedEventManager;

/**
 * Has many relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasMany extends AbstractMultipleRelation implements RelationInterface
{

    /**
     * Sets the entity that defines the relation
     *
     * @param Entity $entity
     *
     * @return self
     */
    public function setEntity(Entity $entity)
    {

        /** @var SharedEventManager $events */
        $events = $this->getContainer()->get('sharedEventManager');
        $events->attach(
            get_class($entity),
            Delete::BEFORE_DELETE,
            array($this, 'onDelete')
        );
        $this->getContainer()->set('sharedEventManager', $events);
        return parent::setEntity($entity);
    }

    /**
     * Tries to guess the foreign key for this relation
     *
     * @return string
     */
    protected function _guessForeignKey()
    {
        $name = explode('\\', $this->getEntity()->getClassName());
        $name = end($name);
        return strtolower($name) .'_id';
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
        /** @var HasMAny $relation */
        $parameters = $annotation->getParameters();
        unset ($parameters['_raw']);
        $relation = new static($parameters);
        $relation->setEntity($entity)->setPropertyName($property);
        $relation->setRelatedEntity($annotation->getValue());
        return $relation;
    }

    /**
     * Lazy loading of relations callback method
     *
     * @param \Slick\Orm\Entity $entity
     * @return Entity|RecordList
     */
    public function load(Entity $entity)
    {
        /** @var Select $sql */
        $sql = call_user_func_array(
            array($this->getRelatedEntity(), 'find'),
            []
        );
        $pmk = $this->getEntity()->getPrimaryKey();
        $sql->where(
            [
                "{$this->getForeignKey()} = :id" => [
                    ':id' => $this->getEntity()->$pmk
                ]
            ]
        );
        $sql->limit($this->getLimit());
        return $sql->all();
    }

    /**
     * Runs before delete on entity event and deletes the children
     * records on related entity.
     *
     * @param Delete $event
     */
    public function onDelete(Delete $event)
    {
        if ($this->isDependent()) {
            $class = $this->getRelatedEntity();
            /** @var Entity $entity */
            $entity = new $class();
            $fkField = $this->getForeignKey();
            $pmk = $event->getTarget()->getPrimaryKey();
            $sql = Sql::createSql($entity->getAdapter())
                ->delete($entity->getTableName())
                ->where(
                    ["{$fkField} = :id" => [':id' => $event->getTarget()->$pmk]]
                );

            $sql->execute();
        }
    }
}
