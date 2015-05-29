<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\AbstractSql;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\ExecuteMethods;
use Slick\Database\Sql\WhereMethods;

/**
 * Drop Table SQL statement
 *
 * @package Slick\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTable extends AbstractSql
{

    /**
     * Use where clause related methods
     */
    use WhereMethods;

    /**
     * Use query execution methods
     */
    use ExecuteMethods;

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }

}