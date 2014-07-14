<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/14/14
 * Time: 5:17 PM
 */

namespace Slick\Database\Sql\Dialect;


use Slick\Database\Sql\Dialect\Sqlite as SqliteDialect;

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
} 