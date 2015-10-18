<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Utils\Collection;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\Collection\AbstractMap;
use Slick\Common\Utils\HashableInterface;

/**
 * AbstractMap Test case
 *
 * @package Slick\Tests\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractMapTest extends TestCase
{

    /**
     * @var AbstractMap
     */
    protected $map;

    /**
     * Set the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $class = 'Slick\Common\Utils\Collection\AbstractMap';
        $this->map = $this->getMockBuilder($class)
            ->setConstructorArgs([['foo' => 'bar', 'baz' => 'boo']])
            ->getMockForAbstractClass();
    }

    public function testGetValue()
    {
        $value = $this->map['baz'];
        $this->assertEquals('boo', $value);
    }

    public function testValueExistence()
    {
        $this->assertTrue(isset($this->map['foo']));
    }

    public function testObjectAsAKey()
    {
        $key = new KeyObject();
        $this->map[$key] = 'test1';
        $this->assertEquals('test1', $this->map[$key]);
    }

    public function testSerialization()
    {
        $data = serialize($this->map);
        $map = unserialize($data);
        $this->assertEquals($this->map, $map);
    }

    public function testGetValues()
    {
        $this->assertEquals(['bar', 'boo'], $this->map->values());
    }

    public function testGetKeys()
    {
        $this->assertEquals(['foo', 'baz'], $this->map->keys());
    }

    public function testRemove()
    {
        $element = $this->map->remove('foo');
        $this->assertEquals('bar', $element);
        unset($this->map['baz']);
        $this->assertTrue($this->map->isEmpty());
    }

    public function testGetInvalidKey()
    {
        $this->setExpectedException(
            'Slick\Common\Exception\InvalidArgumentException'
        );
        $this->map->get('test');
    }

    public function testKeyIsAnHashableObject()
    {
        $key1 = new MyHashable();
        $key1->value = 1;
        $key2 = new MyHashable();
        $key2->value = 2;
        $this->map->set($key1, 'test1')
            ->set($key2, 'test2');
        $this->assertEquals('test2', $this->map->get($key2));
        $this->assertEquals(
            [
                'foo' => 'bar',
                'baz' => 'boo',
                'object-1' => 'test1',
                'object-2' => 'test2'
            ],
            $this->map->asArray()
        );
    }

    public function testClearMap()
    {
        $this->map->clear();
        $this->assertTrue($this->map->isEmpty());
    }
}

class KeyObject
{
    public function __toString()
    {
        return "IAmAnObject";
    }
}

class MyHashable implements HashableInterface
{
    public $value = 0;

    /**
     * Produces a hash for the given object.
     *
     * If two objects are equal (as per the equals() method), the hash()
     * method must produce the same hash for them.
     *
     * The reverse can, but does not necessarily have to be true. That is,
     * if two objects have the same hash, they do not necessarily have to be
     * equal, but the equals() method must be called to be sure.
     *
     * When implementing this method try to use a simple and fast algorithm
     * that produces reasonably different results for non-equal objects, and
     * shift the heavy comparison logic to equals().
     *
     * @return string|integer
     */
    public function hash()
    {
        return spl_object_hash($this);
    }

    /**
     * Whether two objects are equal.
     *
     * This can compare by referential equality (===), or in case of value
     * objects (like \DateTime) compare the individual properties of the
     * objects; it's up to the implementation.
     *
     * @param HashableInterface $other
     *
     * @return bool
     */
    public function equals(HashableInterface $other)
    {
        return $this->hash() == $other->hash();
    }

    /**
     * Returns the string representation for this object
     *
     * @return string
     */
    public function __toString()
    {
        return "object-{$this->value}";
    }
}