<?php

/**
 * ORM Select query
 *
 * @package   Slick\Orm\Sql;
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Sql;

use Slick\Database\RecordList;
use Slick\Database\Sql\Select as DatabaseSelect;
use Slick\Orm\Entity;
use Slick\Orm\Events\Select as SelectEvent;

/**
 * ORM Select query
 *
 * @package   Slick\Orm\Sql;
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends DatabaseSelect
{

    /**
     * @var Entity
     */
    protected $_entity;

    /**
     * Overrides the parent constructor to set the entity dependency
     *
     * @param Entity $entity
     * @param string $fields
     */
    public function __construct(Entity $entity, $fields = '*')
    {
        $this->_table = $entity->getTableName();
        $this->_entity = $entity;
        $this->_fields = $fields;
        $this->_adapter = $entity->getAdapter();
    }

    /**
     * Return all the records for the current query
     *
     * @return \Slick\Database\RecordList|Entity[]
     */
    public function all()
    {
        $events = $this->_entity->getEventManager();
        $event = new SelectEvent(
            [
                'sqlQuery' => &$this,
                'params' => [],
                'action' => SelectEvent::FIND_ALL,
                'singleItem' => false
            ]
        );
        $events->trigger(SelectEvent::BEFORE_SELECT, $this->_entity, $event);
        $result = parent::all();
        $event->data = $result;
        $events->trigger(SelectEvent::AFTER_SELECT, $this->_entity, $event);
        $class = $this->_entity->getClassName();
        $recordList = new RecordList();
        foreach ($result as $row) {
            $recordList[] = new $class($row);
        }
        return $recordList;
    }

    /**
     * Returns the first record for the current query
     *
     * @return Entity
     */
    public function first()
    {
        $events = $this->_entity->getEventManager();
        $event = new SelectEvent(
            [
                'sqlQuery' => &$this,
                'params' => [],
                'action' => SelectEvent::FIND_FIRST,
                'singleItem' => true
            ]
        );
        $events->trigger(SelectEvent::BEFORE_SELECT, $this->_entity, $event);
        $row = parent::first();
        $event->data = $row;
        $events->trigger(SelectEvent::AFTER_SELECT, $this->_entity, $event);
        $class = $this->_entity->getClassName();
        return new $class($row);
    }

    /**
     * Counts all records matching this select query
     *
     * @return int
     */
    public function count()
    {
        $events = $this->_entity->getEventManager();
        $event = new SelectEvent(
            [
                'sqlQuery' => $this,
                'params' => [],
                'action' => SelectEvent::FIND_COUNT,
                'singleItem' => true
            ]
        );
        $events->trigger(SelectEvent::BEFORE_COUNT, $this->_entity, $event);
        $rows = parent::count();
        return $rows;
    }
}
