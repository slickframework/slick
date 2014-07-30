<?php

/**
 * Schema Loader Interface
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;

/**
 * Schema Loader Interface
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface LoaderInterface extends AdapterAwareInterface
{

    /**
     * Returns the schema for the given interface
     *
     * @return SchemaInterface
     */
    public function getSchema();
}
