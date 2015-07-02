<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\Blob;

/**
 * Column type Blob test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class BlobTest extends TestCase
{

    /**
     * @var Blob SUT object
     */
    protected $blob;

    protected function setUp()
    {
        parent::setUp();
        $this->blob = new Blob(
            'image',
            1024,
            ['nullable' => true, 'default' => '123']
        );
    }

    protected function tearDown()
    {
        $this->blob = null;
        parent::tearDown();
    }

    public function testBlobColumn()
    {
        $this->assertEquals('123', $this->blob->getDefault());
        $this->assertEquals('image', $this->blob->getName());
        $this->assertEquals(1024, $this->blob->getLength());
    }

    public function testDefaultValue()
    {
        $result = $this->blob->setDefault('321');
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $result);
        $this->assertEquals('321', $this->blob->getDefault());
    }

    public function testNullable()
    {
        $result = $this->blob->setNullable(false);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $result);
        $this->assertFalse($this->blob->getNullable());
    }
}