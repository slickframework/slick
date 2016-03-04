<?php

/**
 * Dialect definition interface
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\SqlInterface;

/**
 * Dialect definition interface
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DialectInterface
{

    /**
     * Sets the SQL object to be processed
     *
     * @param SqlInterface $sql
     * @return DialectInterface
     */
    public function setSql(SqlInterface $sql);

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    public function getSqlStatement();
}
