<?php

/**
 * ORM Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm;

use Slick\Database\Sql;
use Slick\Orm\Events\Delete;
use Slick\Orm\Events\Save;
use Slick\Utility\Text;
use Slick\Di\Definition;
use Slick\Database\Adapter;
use Slick\Orm\Events\Select;
use Slick\Orm\Entity\Manager;
use Slick\Di\ContainerInterface;
use Slick\Orm\Entity\AbstractEntity;
use Slick\Database\Adapter\AdapterInterface;

/**
 * ORM Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $tableName Database table name
 *
 * @method Entity setTableName(string $name) Sets database table name
 *
 */
class Entity extends AbstractEntity
{

    /**
     * @readwrite
     * @var string
     */
    protected $_tableName;

    /**
     * Returns entity table name. If no name was give it returns the plural
     * of the entity class name
     *
     * @return string
     */
    public function getTableName()
    {
        if (is_null($this->_tableName)) {
            $parts = explode('\\', $this->getClassName());
            $name = end($parts);
            $this->_tableName = Text::plural(strtolower($name));
        }
        return $this->_tableName;
    }

    /**
     * Gets the record with the provided primary key
     *
     * @param string|integer $id The primary key value
     *
     * @return null|self
     */
    public static function get($id)
    {
        /** @var Entity $entity */
        $entity = new static();
        $className = $entity->getClassName();
        $sql = Sql::createSql($entity->getAdapter())
            ->select($entity->getTableName())
            ->where(["{$entity->_primaryKey} = :id" => [':id' => $id]]);
        $events = $entity->getEventManager();
        $event = new Select([
            'sqlQuery' => $sql,
            'params' => compact('id'),
            'action' => Select::GET,
            'singleItem' => true
        ]);
        $events->trigger(Select::BEFORE_SELECT, $entity, $event);
        $row = $event->sqlQuery->first();

        if ($row) {
            $event->data = $row;
            $events->trigger(Select::AFTER_SELECT, $entity, $event);
            $object = new $className($event->data);
            return $object;
        }
        return null;
    }

    /**
     * Starts a select query on this model. Fields can be specified otherwise
     * all fields are selected
     *
     * @param string|array $fields The list of fields to be selected.
     *
     * @return \Slick\Orm\Sql\Select
     */
    public static function find($fields = '*')
    {
        $entity = new static();
        $select = new \Slick\Orm\Sql\Select($entity, $fields);
        return $select;
    }

    /**
     * Saves the entity data or the provided data
     *
     * If no data is provided the save action will check all properties
     * marked with @column annotation and crates a key/value pair array
     * with those properties and its values. If the data is provided only
     * the values in that associative array will be used except that if
     * you don't set the primary key value and the entity has this property
     * set it will be added to the data being saved.
     * If data being saved has the primary key with value (by setting this
     * property or entering the key/value in data) an update will be performed
     * on database. In the opposite an insert will be done.
     *
     * @param array $data
     *
     * @return bool True if data was saved, false otherwise
     */
    public function save(array $data = [])
    {
        $action = Save::INSERT;
        $pmk = $this->primaryKey;
        if ($this->$pmk || isset($data[$pmk])) {
            $action = Save::UPDATE;
        }

        if ($action == Save::UPDATE) {
            return $this->_update($data);
        }

        return $this->_insert($data);
    }

    /**
     * Deletes current record from
     *
     * @return bool
     */
    public function delete()
    {
        $pmk = $this->getPrimaryKey();
        $sql = Sql::createSql($this->getAdapter())
            ->delete($this->getTableName())
            ->where(["{$pmk} = :id" => [':id' => $this->$pmk]]);
        $event = new Delete([
            'primaryKey' => $this->$pmk,
            'abort' => false
        ]);
        $this->getEventManager()->trigger(Delete::BEFORE_DELETE, $this, $event);

        if ($event->abort) {
            return false;
        }
        $result = $sql->execute();
        $this->getEventManager()->trigger(Delete::AFTER_DELETE, $event);
        return $result > 0;
    }

    /**
     * Inserts a new record in the database
     *
     * @param array $data
     * @return bool
     */
    protected function _insert(array $data)
    {
        $data = $this->_setData($data);
        $sql = Sql::createSql($this->getAdapter())->insert($this->getTableName());
        $event = new Save([
            'action' => Save::INSERT,
            'data' => $data,
            'abort' => false
        ]);
        $this->getEventManager()->trigger(Save::BEFORE_SAVE, $this, $event);
        if ($event->abort) {
            return false;
        }
        $result = $sql->set($event->data)->execute();

        if ($result > 0) {
            $pmk = $this->getPrimaryKey();
            $this->$pmk = $this->getAdapter()->getLastInsertId();
            $this->getEventManager()->trigger(Save::AFTER_SAVE, $this, $event);
        }
        return $result > 0;
    }

    /**
     * Updated current entity or data
     *
     * @param array $data
     * @return bool
     */
    protected function _update(array $data)
    {
        $data = $this->_setData($data);
        $pmk = $this->getPrimaryKey();
        $sql = Sql::createSql($this->getAdapter())->update($this->getTableName());
        $event = new Save([
            'action' => Save::UPDATE,
            'data' => $data,
            'abort' => false
        ]);
        $this->getEventManager()->trigger(Save::BEFORE_SAVE, $this, $event);
        if ($event->abort) {
            return false;
        }
        $result = $sql->set($data)
            ->where(["{$pmk} = :id" => [':id' => $this->$pmk]])
            ->execute();
        $this->getEventManager()->trigger(Save::AFTER_SAVE, $this, $event);
        return $result > 0;
    }

    /**
     * Sets the data to be saved
     *
     * @param array $data
     * @return array
     */
    protected function _setData(array $data)
    {
        $pmk = $this->getPrimaryKey();
        if (!empty($data)) {
            if (!isset($data[$pmk]) && !is_null($this->$pmk)) {
                $data[$pmk] = $this->$pmk;
            }
            return $data;
        }
        $columns = Manager::getInstance()->get($this)->getColumns();
        foreach (array_keys($columns) as $property) {
            $key = trim($property, '_');
            if ($key == $pmk && !$this->$pmk) {
                continue;
            }
            $data[$key] = $this->$property;
        }

        return $data;
    }
}
