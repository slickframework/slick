<?php

/**
 * Standard SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\Dialect\Standard as StandardDialect;
use Slick\Filter\StaticFilter;

/**
 * Standard SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Standard extends AbstractDialect implements DialectInterface
{

    /**
     * @var SqlTemplateInterface
     */
    protected $_template;

    /**
     * @var array A map that ties a known Sql class to a factory method
     */
    protected $_map = [
        'insert' => 'Slick\Database\Sql\Insert',
        'select' => 'Slick\Database\Sql\Select',
        'update' => 'Slick\Database\Sql\Update',
        'delete' => 'Slick\Database\Sql\Delete',
        'dropTable' => 'Slick\Database\Sql\Ddl\DropTable',
        'alterTable' => 'Slick\Database\Sql\Ddl\AlterTable',
        'createTable' => 'Slick\Database\Sql\Ddl\CreateTable',
    ];

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    public function getSqlStatement()
    {
        return $this->_getTemplate()->processSql($this->_sql);
    }

    /**
     * Creates the template for current SQL Object
     *
     * @return SqlTemplateInterface
     */
    protected function _getTemplate()
    {
        if (is_null($this->_template)) {
            foreach ($this->_map as $method => $className) {
                if ($this->_sql instanceof $className) {
                    $this->_template = $this->$method();
                    break;
                }
            }
        }
        return $this->_template;
    }

    /**
     * Creates a select sql template
     *
     * @return Standard\SelectSqlTemplate
     */
    public function select()
    {
        return new StandardDialect\SelectSqlTemplate();
    }

    /**
     * Creates a delete sql template
     *
     * @return Standard\DeleteSqlTemplate
     */
    public function delete()
    {
        return new StandardDialect\DeleteSqlTemplate();
    }

    /**
     * Creates an insert sql template
     *
     * @return Standard\InsertSqlTemplate
     */
    public function insert()
    {
        return new StandardDialect\InsertSqlTemplate();
    }

    /**
     * Creates an update sql template
     *
     * @return Standard\UpdateSqlTemplate
     */
    public function update()
    {
        return new StandardDialect\UpdateSqlTemplate();
    }

    /**
     * Crates a create table sql template
     *
     * @return Standard\CreateTableSqlTemplate
     */
    public function createTable()
    {
        return new StandardDialect\CreateTableSqlTemplate();
    }

    /**
     * Creates an alter table sql template
     *
     * @return Standard\AlterTableSqlTemplate
     */
    public function alterTable()
    {
        return new StandardDialect\AlterTableSqlTemplate();
    }

    /**
     * Creates a drop table sql template
     *
     * @return Standard\DropTableSqlTemplate
     */
    public function dropTable()
    {
        return new StandardDialect\DropTableSqlTemplate();
    }
}
