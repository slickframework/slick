<?php

/**
 * Entity serialization
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm;

use Slick\Common\Inspector;

/**
 * Methods for entity serialization and unserialization
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait EntitySerialization
{
    /**
     * @readwrite
     * @var Inspector
     */
    protected $_inspector;

    /**
     * @var array List of tags to exclude
     */
    protected $_invalidTags = [
        '@hasMany', '@hasAndBelongsToMany'
    ];

    /**
     * @var array list of invalid property names
     */
    protected $_invalidProperties =[
        '___mocked', '_connector', '_relationsManager', '_remainingData',
        '_inspector', '_events'
    ];

    /**
     * Implements serialization
     *
     * @returns string The serialized string
     */
    public function serialize()
    {
        $keys = $this->_getKeys();
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->$key;
        }
        return serialize($data);
    }

    /**
     * Returns a list of properties for serialization
     *
     * @return string[]
     */
    protected function _getKeys()
    {
        $keys = array_keys(get_object_vars($this));
        $data = [];
        foreach ($keys as $key) {
            if ($this->_isSerializable($key)) {
                $data[] = $key;
            }
        }
        return $data;
    }

    /**
     * Check if the provided key can be use in serialization
     *
     * @param string $key The property name to evaluate
     *
     * @return boolean True if the provided key can be used in the
     *  serialization, false if not.
     */
    protected function _isSerializable($key)
    {
        if (in_array($key, $this->_invalidProperties)) {
            return false;
        }

        $metaData = $this->getInspector()->getPropertyMeta($key);
        foreach ($this->_invalidTags as $tagName) {
            if ($metaData->hasTag($tagName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Lazy load the class inspector.
     *
     * @return Inspector
     */
    public function getInspector()
    {
        if (is_null($this->_inspector)) {
            $this->_inspector = new Inspector($this);
        }
        return $this->_inspector;
    }
} 