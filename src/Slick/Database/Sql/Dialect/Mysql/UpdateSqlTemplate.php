<?php

/**
 * Update SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Update;
use Slick\Database\Sql\Dialect\Standard\UpdateSqlTemplate as StandardTpl;

/**
 * Update SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UpdateSqlTemplate extends StandardTpl
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|Update $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        $template = "UPDATE %s SET %s WHERE %s";
        return sprintf(
            $template,
            $this->_sql->getTable(),
            $this->_getFieldsAndPlaceholders(),
            $this->_sql->getWhereStatement()
        );
    }
} 