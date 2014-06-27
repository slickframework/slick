<?php

/**
 * Field list aware interface
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Field list aware interface
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
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