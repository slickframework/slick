<?php

/**
 * Sql execute methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

/**
 * Sql execute methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait ExecuteMethods
{
    /**
     * Executes an SQL or DDL query and returns the number of affected rows
     *
     * @return integer The number of affected rows by executing the
     *  query
     */
    public function execute()
    {
        $params = [];
        if (method_exists($this, 'getParameters')) {
            $params = $this->getParameters();
        }
        return $this->_adapter->execute($this, $params);
    }
}
