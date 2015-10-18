<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Utils\Collection;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\Collection\AbstractCollection;

/**
 * Abstract Collection Test Case
 *
 * @package Slick\Tests\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractCollectionTest extends TestCase
{

    /**
     * @var AbstractCollection
     */
    protected $collection;

    /**
     * Create the SUT object instance
     */
    protected function setUp()
    {
        parent::setUp();
        $class = 'Slick\Common\Utils\Collection\AbstractCollection';
        $this->collection = $this->getMockBuilder($class)
            ->setConstructorArgs([[0, 2, 4, 5, 8, 3]])
            ->getMockForAbstractClass();
    }

    /**
     * By default should get an array iterator
     */
    public function testIterator()
    {
        $iterator = $this->collection->getIterator();
        $this->assertInstanceOf('Iterator', $iterator);
    }

    /**
     * Should throw an exception as class does not exists
     */
    public function testUnknownClassIterator()
    {
        $this->setExpectedException(
            'Slick\Common\Exception\InvalidArgumentException'
        );
        $this->collection->setIteratorClass('JustADummyUnExistentClass');
    }

    /**
     * Should throw an exception as class does not implements iterator
     */
    public function testInvalidClassIterator()
    {
        $this->setExpectedException(
            'Slick\Common\Exception\InvalidArgumentException'
        );
        $this->collection->setIteratorClass('stdClass');
    }

    /**
     * Should instantiate the passed iterator class name
     * @test
     */
    public function getDifferentIterator()
    {
        $this->collection->setIteratorClass('RecursiveArrayIterator');
        $iterator = $this->collection->getIterator();
        $this->assertInstanceOf('RecursiveArrayIterator', $iterator);
    }

    /**
     * Should create a collection equals to the one used in serialization
     */
    public function testSerialization()
    {
        $string = serialize($this->collection);
        $collection = unserialize($string);
        $this->assertEquals($this->collection, $collection);
    }

    public function testArrayBehavior()
    {
        $this->assertEquals(4, $this->collection[2]);
        unset($this->collection[0]);
        $this->assertFalse(isset($this->collection[0]));
        $this->assertEquals(5, count($this->collection));
        $this->collection[0] = 1;
        $expected = [1, 2, 4, 5, 8, 3];
        $this->assertEquals($expected, $this->collection->asArray());
    }

    public function testClearAndEmpty()
    {
        $this->assertFalse($this->collection->isEmpty());
        $this->assertTrue($this->collection->clear()->isEmpty());
    }

    public function testCallableOnEachElement()
    {
        $collection = $this->collection->each(function(&$item){
            $item = $item + 10;
            return false;
        });
        $this->assertEquals([10, 2, 4, 5, 8, 3], $collection->asArray());
    }
}
