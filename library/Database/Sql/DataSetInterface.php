<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * DataSet Interface for SQL objects that deals with data
 *
 * @package Slick\Database\Sql
 */
interface DataSetInterface extends SqlInterface
{

    /**
     * Returns a list of field names separated by a comma
     *
     * @return string
     */
    public function getFieldList();

    /**
     * return the placeholder names separated by comma
     *
     * @return string
     */
    public function getPlaceholderList();

    /**
     * Returns the field names
     *
     * @return array
     */
    public function getFields();
}