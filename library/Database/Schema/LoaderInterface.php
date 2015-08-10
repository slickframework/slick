<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;

/**
 * Schema Loader Interface
 *
 * @package Slick\Database\Schema
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface LoaderInterface extends AdapterAwareInterface
{

    /**
     * Returns a list of table names
     *
     * @return string[]
     */
    public function getTables();

    /**
     * Returns the schema for the given interface
     *
     * @return SchemaInterface
     */
    public function getSchema();
}
