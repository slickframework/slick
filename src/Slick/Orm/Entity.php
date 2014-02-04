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

use Slick\Database\RecordList;
use Slick\Di\DependencyInjector;
use Slick\Orm\Entity\Column;
use Slick\Orm\Exception;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string primaryKey
 */
class Entity extends AbstractEntity
    implements EntityInterface, EventManagerAwareInterface
{

    /**
     * @var EventManagerInterface
     */
    protected $_events;

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
     * Retrieves the record with the provided primary key
     *
     * @param int $id The primary key id
     *
     * @return Entity An entity object
     */
    public static function get($id)
    {
        /** @var Entity $entity */
        $entity = new static();
        $className = get_called_class();
        $row = $entity->query()
            ->select($entity->table)
            ->where(["{$entity->table}.{$entity->primaryKey} = ?" => $id]);

        $entity->getEventManager()->trigger(
            'beforeSelect',
            $entity,
            [
                'query' => &$row,
                'id' => $id
            ]
        );

        $row = $row->first();

        if ($row) {
            $object = new $className($row);

            return $object;
        }
        return null;
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

        $rows = $rows->all();
        $result = new RecordList();

        if ($rows && is_a($rows, '\ArrayObject')) {
            foreach ($rows as $row) {
                $result->append(new $className($row));
            }
        }

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

        $row = $row->first();

        if ($row) {
            return new $className($row);
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

        if ($this->$pmKey || isset($data[$pmKey])) {
            $result = $this->_update($data);
        } else {
            $result = $this->_insert($data);
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

        return $this->query()
            ->delete($this->table)
            ->where(["{$pmKey} = ?" => $this->$pmKey])
            ->execute();
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

        $row = $this->query()
            ->select($this->table)
            ->where(["{$pmKey} = ?" => $this->$pmKey])
            ->first();

        if ($row) {
            $this->_hydratate($row);
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
                $data[$col->name] = $this->$prop;
            }
        }

        $query->set($data);
        return $query->save();
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

        $query->set($data);
        return $query->save();
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return Entity
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
            )
        );
        $this->_events = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (is_null($this->_events)) {
            $di = DependencyInjector::getDefault();
            $sharedEvent = $di->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvent);
            $this->setEventManager($events);
        }
        return $this->_events;
    }
}