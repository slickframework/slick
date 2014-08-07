<?php

/**
 * Standard Create Index SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\CreateIndex;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Create Index SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateIndexSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|CreateIndex $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        /** @var CreateIndex $sql */
        $this->_sql = $sql;

        $template = "CREATE INDEX %s ON %s (%s)";

        return sprintf(
            $template,
            $this->_sql->getName(),
            $this->_sql->getTable(),
            implode(', ', $this->_sql->getColumnNames())
        );
    }
}
