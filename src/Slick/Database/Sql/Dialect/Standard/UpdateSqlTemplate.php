<?php

/**
 * Standard Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Update;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UpdateSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
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
        $template = "UPDATE %s SET (%s) WHERE %s";
        return sprintf(
            $template,
            $this->_sql->getTable(),
            $this->_getFieldsAndPlaceholders(),
            $this->_sql->getWhereStatement()
        );
    }

    /**
     * Creates the fields and its placeholders
     *
     * @return string
     */
    protected function _getFieldsAndPlaceholders()
    {
        $fields = $this->_sql->getFields();
        $parts = [];
        foreach ($fields as $field) {
            $parts[] = "{$field} = :{$field}";
        }
        return implode(', ', $parts);
    }
}
