<?php

/**
 * Sqlite SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\Dialect\Sqlite as SqliteDialect;

/**
 * Sqlite SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Sqlite extends Standard
{

    /**
     * Crates a create table sql template
     *
     * @return Standard\CreateTableSqlTemplate
     */
    public function createTable()
    {
        return new SqliteDialect\CreateTableSqlTemplate();
    }

    /**
     * Creates an alter table sql template
     *
     * @return Standard\AlterTableSqlTemplate
     */
    public function alterTable()
    {
        return new SqliteDialect\AlterTableSqlTemplate();
    }

    /**
     * Creates an update sql template
     *
     * @return Standard\UpdateSqlTemplate
     */
    public function update()
    {
        return new SqliteDialect\UpdateSqlTemplate();
    }

    /**
     * Creates a select sql template
     *
     * @return Standard\SelectSqlTemplate
     */
    public function select()
    {
        return new SqliteDialect\SelectSqlTemplate();
    }
}
