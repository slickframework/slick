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
            $parts = explode('\\', get_class($this));
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

    public function save(array $data = [])
    {
        $action = 'insert';
        $pmk = $this->primaryKey;
        if ($this->$pmk || isset($data[$pmk])) {
            $action = 'update';
        }

        if ($action == 'update') {
            return $this->_update($data);
        }

        return $this->_insert($data);
    }

    protected function _insert(array $data)
    {
        $data = $this->_setData($data);
        $sql = Sql::createSql($this->getAdapter())->insert($this->getTableName());
        $result = $sql->set($data)->execute();

        if ($result > 0) {
            $pmk = $this->getPrimaryKey();
            $this->$pmk = $this->getAdapter()->getLastInsertId();
        }
        return $result > 0;
    }

    protected function _update(array $data)
    {
        $data = $this->_setData($data);
        $pmk = $this->getPrimaryKey();
        $sql = Sql::createSql($this->getAdapter())->update($this->getTableName());
        $result = $sql->set($data)
            ->where(["{$pmk} = :id" => [':id' => $this->$pmk]])
            ->execute();
        return $result > 0;
    }

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
            $data[$key] = $this->$property;
        }

        return $data;
    }
}
