<?php

/**
 * Standard Drop Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\DropTable;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Drop Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTableSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|DropTable $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $tableName = $sql->getTable();
        return "DROP TABLE {$tableName}";
    }
}
