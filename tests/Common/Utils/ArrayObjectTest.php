<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Utils;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\ArrayObject;

/**
 * ArrayObject Test case
 *
 * @package Slick\tests\Common\Utils
 */
class ArrayObjectTest extends TestCase
{

    /**
     * @var string
     */
    protected $serialized;

    /**
     * @var ArrayObject
     */
    protected $unserialized;

    protected function setup()
    {
        parent::setUp();
        $obj = new ArrayObject(['foo' => 'bar', 'baz']);
        $this->serialized = serialize($obj);
        $this->unserialized = $obj;
    }

    protected function tearDown()
    {
        $this->serialized = null;
        $this->unserialized = null;
        parent::tearDown();
    }

    public function testSerialization()
    {
        $this->assertEquals(
            $this->unserialized,
            unserialize($this->serialized)
        );
    }
}
