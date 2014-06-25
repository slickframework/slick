<?php

/**
 * Delete SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

/**
 * Delete SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Delete extends AbstractSql implements SqlInterface
{

    /**
     * @var string
     */
    private $_table;

    /**
     * Use where clause constructor methods
     */
    use WhereMethods;

    /**
     * Creates the sql with the table name and fields
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->_table = $tableName;
    }

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        // TODO: Implement getQueryString() method.
    }

    /**
     * Executes the delete SQL command
     *
     * @return int The number of affected rows
     */
    public function execute()
    {
        return $this->_adapter->execute(
            $this->getQueryString(),
            $this->getParameters()
        );
    }
}
