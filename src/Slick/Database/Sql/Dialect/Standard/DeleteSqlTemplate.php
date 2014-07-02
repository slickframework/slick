<?php

/**
 * Standard Delete SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Delete SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DeleteSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        $this->_statement = "DELETE FROM {$this->_sql->getTable()}";
        $this->_getWhereConditions();
        return $this->_statement;
    }
}
