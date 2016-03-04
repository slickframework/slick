<?php

/**
 * Standard Drop Index SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\DropIndex;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Drop Index SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropIndexSqlTemplate extends AbstractSqlTemplate implements
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
        /** @var DropIndex $sql */
        $this->_sql = $sql;

        $template = "DROP INDEX %s ON %s";

        return sprintf($template, $sql->getName(), $sql->getTable());
    }
}
