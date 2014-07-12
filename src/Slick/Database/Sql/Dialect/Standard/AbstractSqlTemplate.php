<?php

/**
 * Abstract SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\CreateIndex;
use Slick\Database\Sql\Delete;
use Slick\Database\Sql\Insert;
use Slick\Database\Sql\Update;
use Slick\Database\Sql\Select;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Abstract SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSqlTemplate implements SqlTemplateInterface
{

    /**
     * @var SqlInterface|Select|Delete|Insert|Update|CreateTable|AlterTable|CreateIndex
     */
    protected $_sql;

    /**
     * @var string
     */
    protected $_statement = '';

    /**
     * Sets the where clause for this select statement
     *
     * @return SelectSqlTemplate
     */
    protected function _getWhereConditions()
    {
        $where = $this->_sql->getWhereStatement();
        if ($where) {
            $this->_statement .=  " WHERE {$where}";
        }
        return $this;

    }
}
