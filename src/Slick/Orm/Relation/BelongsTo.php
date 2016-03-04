<?php

/**
 * Belongs to relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Database\RecordList;
use Slick\Common\Inspector\Annotation;
use Slick\Orm\Events\Save;
use Slick\Orm\RelationInterface;
use Slick\Orm\Sql\Select;
use Zend\EventManager\SharedEventManager;

/**
 * Belongs to relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BelongsTo extends AbstractSingleRelation implements RelationInterface
{

    /**
     * Tries to guess the foreign key for this relation
     *
     * @return string
     */
    protected function _guessForeignKey()
    {
        $descriptor = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $name = explode('\\', $descriptor->getEntity()->getClassName());
        $name = end($name);
        return strtolower($name) . '_id';
    }



    /**
     * Lazy loading of relations callback method
     *
     * @param \Slick\Orm\Entity $entity
     * @return Entity|RecordList
     */
    public function load(Entity $entity)
    {
        $data = $entity->getRawData();
        if (!is_array($data) || !isset($data[$this->getForeignKey()])) {
            return null;
        }
        /** @var Select $sql */
        $sql = call_user_func_array(
            [$this->getRelatedEntity(), 'find'],
            []
        );
        $pmk = Entity\Manager::getInstance()->get($this->getRelatedEntity())
            ->getEntity()->getPrimaryKey();
        $table = Entity\Manager::getInstance()->get($this->getRelatedEntity())
            ->getEntity()->getTableName();
        $sql-> where(
            [
                "{$table}.{$pmk} = :id" => [
                    ':id' => $data[$this->getForeignKey()]
                ]
            ]
        );
        return $sql->first();
    }

    /**
     * Runs before save to set the relation data to be saved
     *
     * @param Save $event
     */
    public function beforeSave(Save $event)
    {
        $entity = $event->getTarget();
        $property = $this->getPropertyName();
        $field = $this->getForeignKey();
        $data = $event->data;
        if (isset($entity->$property) && !is_null($entity->$property)) {
            /** @var Entity $object */
            $object = $entity->$property;
            $data[$field] = $object;
            $class = $this->getRelatedEntity();
            if ($object instanceof $class) {
                $pmk = $object->getPrimaryKey();
                $data[$field] = $object->$pmk;
            }
            $event->data = $data;
        }
    }

    /**
     * Sets the join information on the select query when lazy load is false
     *
     * @param \Slick\Orm\Events\Select $event
     */
    public function beforeSelect(\Slick\Orm\Events\Select $event)
    {
        if ($this->lazyLoad) {
            return;
        }
        $related = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $relatedTable = $related->getEntity()->getTableName();
        $sql = $event->sqlQuery;
        $columns = $related->getColumns();
        $fields = [];
        foreach (array_keys($columns) as $column) {
            $name = trim($column, '_');
            $fields[] = "{$name} AS {$relatedTable}_{$name}";
        }
        $pmk = $related->getEntity()->getPrimaryKey();
        $ent = $this->getEntity()->getTableName();
        $clause  = "{$relatedTable}.{$pmk} = {$ent}.{$this->getForeignKey()}";
        $sql->join($relatedTable, $clause, $fields);
        $event->sqlQuery = $sql;
    }


}
