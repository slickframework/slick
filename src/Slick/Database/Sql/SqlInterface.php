<?php

/**
 * Sql interface
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

use Slick\Database\Adapter\AdapterInterface;

/**
 * Sql interface
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface SqlInterface
{

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString();

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return SqlInterface
     */
    public function setAdapter(AdapterInterface $adapter);

} 