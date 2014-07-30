<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/30/14
 * Time: 5:08 PM
 */

namespace Slick\Database\Schema\Loader;


use Slick\Common\BaseMethods;
use Slick\Database\Schema\LoaderInterface;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Adapter\AdapterAwareInterface;
use Slick\Database\Schema;

class Mysql implements LoaderInterface
{

    /**
     * Factory behavior methods from Slick\Common\Base class
     */
    use BaseMethods;

    /**
     * Easy construction with base methods
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return AdapterAwareInterface
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        // TODO: Implement setAdapter() method.
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        // TODO: Implement getAdapter() method.
    }

    /**
     * Returns the schema for the given interface
     *
     * @return SchemaInterface
     */
    public function getSchema()
    {
        return new Schema();
    }
}