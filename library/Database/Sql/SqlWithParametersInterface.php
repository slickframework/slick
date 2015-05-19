<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 *  SQL Query interface that can have parameters to be bind on query execution
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SqlWithParametersInterface extends SqlInterface
{

    /**
     * Returns the parameters for current query
     *
     * @return array
     */
    public function getParameters();
}