<?php
/**
 * Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm;

use Slick\Common\EventManagerMethods;
use Slick\Database\RecordList;
use Slick\Orm\Entity\Column;
use Slick\Orm\Exception;
use Zend\EventManager\EventManagerAwareInterface;
use Serializable;

/**
 * Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string primaryKey
 */
class Entity extends AbstractEntity
    implements EntityInterface, EventManagerAwareInterface, Serializable
{

    /**
     * Methods for entity serialization and unserialization
     */
    use EntitySerialization;

    /**
     * @readwrite
     * @var array Default query options
     */
    protected $_options = [
        'conditions' => [],
        'fields' => ['*'],
        'order' => null,
        'limit' => null,
        'page' => 0
    ];

    /**
     * Default implementation for EventManagerAwareInterface interface
     */
    use EventManagerMethods;

    /**
     * Retrieves the record with the provided primary key
     *
     * @param int $key The primary key id
     *
     * @return Entity An entity object
     */
    public static function get($key)
    {
        /** @var Entity $entity */
        $entity = new static();
        $className = get_called_class();
        $query = $entity->query()
            ->select($entity->table)
            ->where(["{$entity->table}.{$entity->primaryKey} = ?" => $key]);

        $entity->getEventManager()->trigger(
            'beforeSelect',
            $entity,
            [
                'query' => &$query,
                'id' => $key,
                'action' => 'get'
            ]
        );

        $row = $query->first();

        if ($row) {
            $object = new $className($row);
            $row = $object->remainingData;
            $entity->getEventManager()->trigger(
                'afterSelect',
                $object,
                [
                    'data' => &$row,
                    'entity' => &$object,
                    'action' => 'get'
                ]
            );
            return $object;
        }
        return null;
    }

    /**
     * Queries the database to retrieve the total rows for provided conditions
     *
     * The options are:
     *
     *  - conditions: an array of conditions to filter out records;
     *
     * @param array $options Options to filter out the records
     *
     * @return integer The total rows for current conditions
     */
    public static function count(array $options = array())
    {
        /** @var Entity $entity */
        $entity = new static();
        $options = array_merge($entity->_options, $options);

        $rows = $entity->query()
            ->select($entity->table)
            ->where($options['conditions']);


        $entity->getEventManager()->trigger(
            'beforeSelect',
            $entity,
            [
                'query' => &$rows,
                'action' => 'count'
            ]
        );

        return $rows->count();
    }

    /**
     * Queries the database to retrieves all records that satisfies the
     * conditions and limitations provided by $options.
     *
     * The options are:
     *
     *  - conditions: an array of conditions to filter out records;
     *  - fields: an array with field names to retrieve;
     *  - order: an array or string with order clauses;
     *  - limit: the number of records to select;
     *  - page: the starting page for selected records;
     *
     * @param array $options Options to filter out the records
     *
     * @return RecordList A record list
     */
    public static function all(array $options = array())
    {
        /** @var Entity $entity */
        $entity = new static();
        $className = get_called_class();
        $options = array_merge($entity->_options, $options);

        $rows = $entity->query()
            ->select($entity->table, $options['fields'])
            ->where($options['conditions']);

        if (!is_null($options['order'])) {
            $rows->orderBy($options['order']);
        }

        if (!is_null($options['limit'])) {
            $rows->limit($options['limit'], $options['page']);
        }


        $entity->getEventManager()->trigger(
            'beforeSelect',
            $entity,
            [
                'query' => &$rows,
                'action' => 'all'
            ]
        );

        $rows = $rows->all();
        $result = new RecordList();

        if ($rows && is_a($rows, '\ArrayObject')) {
            foreach ($rows as &$row) {
                $object = new $className($row);
                $row = $object->remainingData;
                $result->append($object);
            }
        }

        $entity->getEventManager()->trigger(
            'afterSelect',
            $entity,
            [
                'data' => &$rows,
                'entity' => &$result,
                'action' => 'all'
            ]
        );

        return $result;
    }

    /**
     * Queries the database to retrieve the first record that satisfies the
     * conditions and limitations provided by $options.
     *
     * The options are:
     *
     *  - conditions: an array of conditions to filter out records;
     *  - files: an array with field names to retrieve;
     *  - order: an array or string with order clauses;
     *
     * @param array $options Options to filter out the records
     *
     * @return Entity An entity object
     */
    public static function first(array $options = array())
    {
        /** @var Entity $entity */
        $entity = new static();
        $className = get_called_class();
        $options = array_merge($entity->_options, $options);

        $row = $entity->query()
            ->select($entity->table, $options['fields'])
            ->where($options['conditions']);

        if (!is_null($options['order'])) {
            $row->orderBy($options['order']);
        }

        $entity->getEventManager()->trigger(
            'beforeSelect',
            $entity,
            [
                'query' => &$row,
                'action' => 'first'
            ]
        );

        $row = $row->first();

        if ($row) {
            $object = new $className($row);
            $row = $object->remainingData;
            $entity->getEventManager()->trigger(
                'afterSelect',
                $object,
                [
                    'data' => &$row,
                    'entity' => &$object,
                    'action' => 'first'
                ]
            );
            return $object;
        }
        return null;
    }

    /**
     * Saves current record data
     *
     * This method will figure out if the save operation is an insert
     * or an update based on the value of the primary key field. If
     * the primary key field is null it will insert and create a new
     * record, if the field isn't null an update will be performed
     * in the record that have that primary key value.
     * If $data param is provided only the keys in that array that
     * match the fields of this table will be updated. If no primary
     * key is used it will figure out from object primary key value
     * if the save operations is an insert or an update.
     *
     * @param array $data A key/value pair of values to be save.
     *
     * @return boolean True if record was successfully saved, false otherwise
     */
    public function save(array $data = array())
    {
        $pmKey = $this->primaryKey;
        $abort = false;
        $result = false;
        $action = 'insert';
        if ($this->$pmKey || isset($data[$pmKey])) {
            $action = 'update';
        }
        $this->getEventManager()->trigger(
            'beforeSave',
            $this,
            array(
                'data' => &$data,
                'abort' => &$abort,
                'action' => $action
            )
        );

        if (!$abort) {
            if ($action == 'update') {
                $result = $this->_update($data);
                $this->getEventManager()->trigger(
                    'afterSave',
                    $this,
                    array(
                        'data' => &$data,
                        'action' => $action
                    )
                );
            } else {
                $result = $this->_insert($data);
                $this->getEventManager()->trigger(
                    'afterSave',
                    $this,
                    array(
                        'data' => &$data,
                        'action' => $action
                    )
                );
            }
        }

        return $result;
    }

    /**
     * Deletes current record from database
     *
     * @throws Exception\PrimaryKeyException if primary key is unset or invalid
     *
     * @return boolean True if record was successfully deleted, false otherwise
     */
    public function delete()
    {
        $pmKey = $this->primaryKey;
        $hasPk = $this->getColumns()->hasPrimaryKey();

        if (!($hasPk && $this->$pmKey)) {
            throw new Exception\PrimaryKeyException(
                "{$this->alias} entity does not have a primary key defined. " .
                "Primary key is null or unset."
            );
        }

        $abort = false;
        $result = false;
        $this->getEventManager()->trigger(
            'beforeDelete',
            $this,
            array(
                'abort' => &$abort
            )
        );

        if (!$abort) {
            $result =  $this->query()
                ->delete($this->table)
                ->where(["{$pmKey} = ?" => $this->$pmKey])
                ->execute();

            $this->getEventManager()->trigger(
                'afterDelete',
                $this,
                array()
            );
        }
        return $result;
    }

    /**
     * Loads the data from database for current object pk value
     *
     * @throws Exception\PrimaryKeyException if primary key is unset or invalid
     *
     * @return Entity A self instance for method chain calls
     */
    public function load()
    {
        $pmKey = $this->primaryKey;
        $hasPk = $this->getColumns()->hasPrimaryKey();

        if (!($hasPk && $this->$pmKey)) {
            throw new Exception\PrimaryKeyException(
                "{$this->alias} entity does not have a primary key defined. " .
                "Primary key is null or unset."
            );
        }

        $query = $this->query()
            ->select($this->table)
            ->where(["{$this->table}.{$this->primaryKey} = ?" => $this->$pmKey]);


        $this->getEventManager()->trigger(
            'beforeSelect',
            $this,
            [
                'query' => &$query,
                'action' => 'load'
            ]
        );

        $row = $query->first();

        if ($row) {
            $this->_hydrate($row);
            $row = $this->remainingData;
            $this->getEventManager()->trigger(
                'afterSelect',
                $this,
                [
                    'data' => &$row,
                    'entity' => &$this,
                    'action' => 'load'
                ]
            );
        }

        return $this;
    }

    /**
     * Insert current or provided data to this entity
     * @param array $data
     * @return bool
     */
    protected function _insert(array $data = [])
    {
        $query = $this->query()
            ->insert($this->getTable());

        if (empty($data)) {
            $columns = $this->getColumns();
            $data = [];
            /** @var Column $col */
            foreach ($columns as $col) {
                $prop = $col->raw;
                if ($col->primaryKey && empty($this->$prop)) {
                    continue;
                }
                $data[$col->name] = $this->$prop;
            }

            //$this->_saveRelations($data);
        }

        $this->getEventManager()->trigger(
            'prepareForInsert',
            $this,
            [
                'query' => &$query,
                'data' => &$data,
                'raw' => $this->_raw
            ]
        );

        $query->set($data);
        $result =  $query->save();

        $this->getEventManager()->trigger(
            'afterInsert',
            $this,
            [
                'result' => $result,
                'data' => &$data,
                'raw' => $this->_raw
            ]
        );

        return $result;
    }

    /**
     * Updated current or provided data on this entity
     * @param array $data
     * @return bool
     */
    protected function _update(array $data = [])
    {
        $pmKey = $this->primaryKey;
        $pmkValue = $this->$pmKey;

        if (isset($data[$pmKey])) {
            $pmkValue = $data[$pmKey];
            unset($data[$pmKey]);
        }

        $query = $this->query()
            ->update($this->getTable())
            ->where(["{$pmKey} = :id" => [':id' => $pmkValue]]);

        if (empty($data)) {
            $columns = $this->getColumns();
            $data = [];
            /** @var Column $col */
            foreach ($columns as $col) {
                if ($col->name == $pmKey) {
                    continue;
                }
                $prop = $col->raw;
                $data[$col->name] = $this->$prop;
            }
        }

        $this->getEventManager()->trigger(
            'prepareForUpdate',
            $this,
            [
                'query' => &$query,
                'data' => &$data,
                'raw' => $this->_raw
            ]
        );
        $query->set($data);
        $result =  $query->save();

        $this->getEventManager()->trigger(
            'afterUpdate',
            $this,
            [
                'result' => $result,
                'data' => &$data,
                'raw' => $this->_raw
            ]
        );

        return $result;
    }


}