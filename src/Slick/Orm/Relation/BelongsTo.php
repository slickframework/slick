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
use Slick\Orm\Sql\Select;
use Zend\EventManager\SharedEventManager;

/**
 * Belongs to relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BelongsTo extends AbstractSingleRelation
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
            Save::BEFORE_SAVE,
            [$this, 'beforeSave']
        );
        $events->attach(
            get_class($entity),
            \Slick\Orm\Events\Select::BEFORE_SELECT,
            [$this, 'beforeSelect']
        );
        $events->attach(
            get_class($entity),
            \Slick\Orm\Events\Select::AFTER_SELECT,
            [$this, 'afterSelect']
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
        $descriptor = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $name = explode('\\', $descriptor->getEntity()->getClassName());
        $name = end($name);
        return strtolower($name) . '_id';
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

        /** @var BelongsTo $relation */
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
            [$this->getRelatedEntity(), 'find'],
            []
        );
        $pmk = Entity\Manager::getInstance()->get($this->getRelatedEntity())
            ->getEntity()->getPrimaryKey();

        $sql-> where(
            [
                "{$pmk} = :id" => [
                    ':id' => $entity->getRawData()[$this->getForeignKey()]
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

    /**
     * Fixes the data to be sent to entity creation with related entity object
     *
     * @param \Slick\Orm\Events\Select $event
     */
    public function afterSelect(\Slick\Orm\Events\Select $event)
    {
        if ($this->lazyLoad) {
            return;
        }
        $data = $event->data->getArrayCopy();
        $related = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $relatedTable = $related->getEntity()->getTableName();
        $class = $related->getEntity()->getClassName();
        foreach ($data as $key => $row) {
            $pmk =  $this->getEntity()->getPrimaryKey();
            if (isset($row[$pmk]) && is_array($row[$pmk])) {
                $data[$key][$pmk] = reset($row[$pmk]);
            }
            $options = [];
            foreach ($row as $column => $value) {
                if (preg_match('/'.$relatedTable.'_(.*)/i', $column)) {
                    unset($data[$key][$column]);
                    $name = str_replace($relatedTable.'_', '', $column);
                    $options[$name] = $value;
                }
            }

            $data[$key][$this->getPropertyName()] = new $class($options);
        }

        $event->data = $data;
    }
}
