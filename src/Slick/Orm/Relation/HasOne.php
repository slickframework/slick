<?php

/**
 * Has one relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Orm\Entity;
use Slick\Orm\Events\Save;
use Slick\Orm\Events\Select;
use Slick\Database\RecordList;
use Slick\Orm\RelationInterface;

/**
 * Has one relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasOne extends AbstractSingleRelation implements RelationInterface
{

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
     * Runs before save to set the relation data to be saved
     *
     * @param Save $event
     */
    public function beforeSave(Save $event)
    {
        return;
    }

    /**
     * Sets the join information on the select query when lazy load is false
     *
     * @param Select $event
     */
    public function beforeSelect(Select $event)
    {
        if ($this->lazyLoad) {
            return;
        }

        $related = Entity\Manager::getInstance()
            ->get($this->getRelatedEntity());
        $relatedTable = $related->getEntity()->getTableName();

        $columns = $related->getColumns();
        $fields = [];
        foreach (array_keys($columns) as $column) {
            $name = trim($column, '_');
            $fields[] = "{$name} AS {$relatedTable}_{$name}";
        }

        $sql = $event->sqlQuery;

        $pmk = $this->getEntity()->primaryKey;
        $fnk = $this->getForeignKey();
        $ent = $this->getEntity()->getTableName();
        $clause = "{$relatedTable}.{$fnk} = {$ent}.{$pmk}";

        $sql->join($relatedTable, $clause, $fields);
        $event->sqlQuery = $sql;
    }

    /**
     * Lazy loading of relations callback method
     *
     * @param Entity $entity
     * @return Entity|RecordList
     */
    public function load(Entity $entity)
    {
        /** @var \Slick\Orm\Sql\Select $sql */
        $sql = call_user_func_array(
            array($this->getRelatedEntity(), 'find'),
            []
        );
        $pmk = $this->getEntity()->getPrimaryKey();
        $table = Entity\Manager::getInstance()->get($this->getRelatedEntity())
            ->getEntity()->getTableName();
        $sql->where(
            [
                "{$table}.{$this->getForeignKey()} = :id" => [
                    ':id' => $this->getEntity()->$pmk
                ]
            ]
        );
        return $sql->first();
    }
}
