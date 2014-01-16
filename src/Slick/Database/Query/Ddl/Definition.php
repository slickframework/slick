<?php

/**
 * Definition
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl;

/**
 * Definition
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Definition extends AbstractDdl
{
    /**
     * Executes the drop query.
     *
     * @return \Slick\Database\RecordList A record list with the query results
     */
    public function execute()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute();
    }
}
