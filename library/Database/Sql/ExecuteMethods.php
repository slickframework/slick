<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Sql execute methods
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
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
        return $this->adapter->execute($this, $params);
    }
}