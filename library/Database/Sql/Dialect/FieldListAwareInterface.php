<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Field list aware interface
 *
 * @package Slick\Database\Sql\Dialect
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface FieldListAwareInterface
{

    /**
     * Returns the list of fields
     *
     * @return string|string[]
     */
    public function getFields();

    /**
     * Returns object SQL alias name
     *
     * @return string|null
     */
    public function getAlias();

    /**
     * Returns object SQL table name
     *
     * @return string
     */
    public function getTable();
}