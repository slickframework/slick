<?php

/**
 * Drop
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl;

/**
 * Drop
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Drop extends AbstractDdl
{
    /**
     * Executes the drop query.
     * 
     * @return boolean True if query was executed successfully
     */
    public function execute()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute();
    }
}