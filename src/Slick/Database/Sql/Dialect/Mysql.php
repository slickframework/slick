<?php

/**
 * Mysql SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Mysql SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends Standard implements DialectInterface
{
    /**
     * Crates a create table sql template
     *
     * @return Standard\CreateTableSqlTemplate
     */
    public function createTable()
    {
        return new Mysql\CreateTableSqlTemplate();
    }

    /**
     * Creates an alter table sql template
     *
     * @return Standard\AlterTableSqlTemplate
     */
    public function alterTable()
    {
        return new Mysql\AlterTableSqlTemplate();
    }
} 