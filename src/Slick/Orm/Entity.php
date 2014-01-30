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
use Slick\Di\DiAwareInterface;

/**
 * Entity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Entity extends AbstractEntity
    implements EntityInterface, DiAwareInterface
{

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
            ->where(["{$entity->primaryKey} = ?" => $id])
            ->first();
        if ($row)
            return new $className($row);
        return null;
    }

    /**
     * Queries the database to retrieves all records that satisfies the
     * conditions and limitations provided by $options.
     *
     * The options are:
     *
     *  - conditions: an array of conditions to filter out records;
     *  - files: an array with field names to retrieve;
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
        // TODO: Implement all() method.
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
        // TODO: Implement first() method.
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
        // TODO: Implement save() method.
    }

    /**
     * Deletes current record from database
     *
     * @return boolean True if record was successfully deleted, false otherwise
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Loads the data from database for current object pk value
     *
     * @return Entity A self instance for method chain calls
     */
    public function load()
    {
        // TODO: Implement load() method.
    }


}