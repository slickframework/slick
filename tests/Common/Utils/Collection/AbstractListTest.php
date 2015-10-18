<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Utils\Collection;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\Collection\AbstractList;

/**
 * AbstractList Test Case
 *
 * @package Slick\Tests\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractListTest extends TestCase
{

    /**
     * @var AbstractList
     */
    protected $list;

    /**
     * Sets the SUT object instance
     */
    protected function setUp()
    {
        parent::setUp();
        $class = 'Slick\Common\Utils\Collection\AbstractList';
        $this->list = $this->getMockBuilder($class)
            ->setConstructorArgs([[1, 2, 3, 4, 5, 6, 7, 8, 9, 0]])
            ->getMockForAbstractClass();
    }

    /**
     * Should read the value at position 3, zero base
     * @test
     */
    public function getValuesAtPosition()
    {
        $this->assertEquals(4, $this->list->get(3));
    }

    /**
     * Should add the value to the end of the list and update
     * the size of the list.
     * @test
     */
    public function addValue()
    {
        $this->list->add('10');
        $this->assertEquals(10, $this->list->get(10));
        $this->assertEquals(11, $this->list->getSize());
    }

    /**
     * Should update the value on the index position
     * @test
     */
    public function updateValue()
    {
        $this->list->update(9, 10);
        $this->assertEquals(10, $this->list->get(9));
    }

    /**
     * Should keep the indexes with no gaps
     * @test
     */
    public function removeElement()
    {
        $element = $this->list->remove(5);
        $this->assertEquals(6, $element);
        $this->assertEquals(9, $this->list->getSize());
        $this->assertEquals(8, $this->list->get(6));
    }
}
