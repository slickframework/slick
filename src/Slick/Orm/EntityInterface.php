<?php

/**
 * EntityInterface
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm;

/**
 * EntityInterface
 *
 * This interface defines the behavior for a database entity with methods to
 * retrieve, edit and remove records from a database table.
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface EntityInterface
{
    /**
     * Retrieves the record with the provided primary key
     *
     * @param int $id The primary key id
     *
     * @return EntityInterface An entity object
     */
    public static function get($id);

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
     * @return \Slick\Database\RecordList A record list
     */
    public static function all(array $options = array());

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
     * @return EntityInterface An entity object
     */
    public static function first(array $options = array());

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
    public function save(array $data = array());

    /**
     * Deletes current record from database
     * 
     * @return boolean True if record was successfully deleted, false otherwise
     */
    public function delete();

    /**
     * Loads the data from database for current object pk value
     *
     * @return EntityInterface A self instance for method chain calls
     */
    public function load();

    /**
     * Returns a query object for custom queries
     *
     * @param null $sql A custom sql
     *
     * @return \Slick\Database\Query\QueryInterface A query interface for
     *  custom queries
     */
    public function query($sql = null);

    /**
     * Returns the database connector (adapter)
     *
     * @return Slick\Database\Connector\ConnectorInterface
     */
    public function getConnector();
}