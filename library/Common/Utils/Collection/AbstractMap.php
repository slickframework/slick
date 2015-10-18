<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils\Collection;

use Slick\Common\Exception\InvalidArgumentException;
use Slick\Common\Utils\HashableInterface;

/**
 * Basic map interface implementation
 *
 * @package Slick\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractMap extends AbstractCollection implements MapInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $keys = [];

    /**
     * @var string
     */
    private $lastOffset;

    /**
     * Creates the collection with provided data
     *
     * @param array|\Traversable $data
     */
    public function __construct($data = [])
    {
        if ($data instanceof \Traversable || is_array($data)) {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Puts a new element in the map.
     *
     * @param string|int|HashableInterface $key
     * @param mixed $value
     *
     * @return $this|self|MapInterface
     */
    public function set($key, $value)
    {
        $offset = $this->getKeyOffset($key);
        $this->keys[$offset] = $key;
        $this->data[$offset] = $value;
        return $this;
    }

    /**
     * Returns the value associated with the given key.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->containsKey($key)) {
            throw new InvalidArgumentException(
                "Provided key does not exists in the map."
            );
        }

        return $this->data[$this->lastOffset];
    }

    /**
     * Removes an element from the map and returns it.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        $element = $this->get($key);
        unset($this->keys[$this->lastOffset]);
        unset($this->data[$this->lastOffset]);
        return $element;
    }

    /**
     * Returns whether this map contains a given key.
     *
     * @param mixed $key
     *
     * @return boolean
     */
    public function containsKey($key)
    {
        $offset = $this->getKeyOffset($key);
        return array_key_exists($offset, $this->keys);
    }

    /**
     * Returns an array with the keys.
     *
     * @return array
     */
    public function keys()
    {
        return array_values($this->keys);
    }

    /**
     * Returns an array with the values.
     *
     * @return array
     */
    public function values()
    {
        return array_values($this->data);
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
       return $this->containsKey($offset);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        $data = [
            'data' => $this->data,
            'keys' => $this->keys,
            'offset' => $this->lastOffset
        ];
        return serialize($data);
    }

    /**
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized The string representation of the object.
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->data = $data['data'];
        $this->keys = $data['keys'];
        $this->lastOffset = $data['offset'];
    }

    /**
     * Returns current collection as an array
     *
     * @return array
     */
    public function asArray()
    {
        $data = [];
        foreach ($this->keys as $hash => $key) {
            $data[(string) $key] = $this->data[$hash];
        }
        return $data;
    }

    /**
     * Removes all of the elements from this collection
     *
     * @return self|$this|MapInterface|AbstractMap
     */
    public function clear()
    {
        $this->data = [];
        $this->keys = [];
        return $this;
    }

    /**
     * Returns the hash offset for provided key
     *
     * If key already exists its has will be used instead
     *
     * @param string|int|HashableInterface $newKey
     *
     * @return string
     */
    private function getKeyOffset($newKey)
    {
        $newHash = $this->getHash($newKey);
        foreach ($this->keys as $hash => $key)
        {
            if (
                $key instanceof HashableInterface &&
                $newKey instanceof HashableInterface
            ) {
                if ($key->equals($newKey)) {
                    $newHash = $hash;
                    break;
                }
                continue;
            }

            if ($newKey === $key) {
                $newHash = $hash;
                break;
            }
        }
        $this->lastOffset = $newHash;
        return $newHash;
    }

    /**
     * Gets the hash for provided key.
     *
     * @param string|int|HashableInterface $key
     *
     * @return string
     */
    private function getHash($key)
    {
        if ($key instanceof HashableInterface) {
            return $key->hash();
        }
        return $this->getObjectHash($key);
    }

    /**
     * Returns an hash for provided object.
     *
     * If not an object it will try to get the hash by calling
     * getScalarHash() method.
     *
     * @param mixed|object $object
     *
     * @return string
     */
    private function getObjectHash($object)
    {
        if (is_object($object)) {
            return spl_object_hash($object);
        }
        return $this->getScalarHash($object);
    }

    /**
     * Creates an hash for provided values
     *
     * @param mixed $value
     *
     * @return string
     */
    private function getScalarHash($value)
    {
        $serialized = serialize($value);
        return md5($serialized);
    }
}
