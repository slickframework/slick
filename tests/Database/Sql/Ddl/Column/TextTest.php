<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;

/**
 * Class TextTest
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TextTest extends TestCase
{
    /**
     * @var Text
     */
    protected $text;

    protected function setUp()
    {
        parent::setUp();
        $this->text = new Text(
            'name',
            ['nullable' => true, 'size' => Size::medium()]
        );
    }

    protected function tearDown()
    {
        $this->text = null;
        parent::tearDown();
    }

    public function testTextColumn()
    {
        $this->assertEquals(Size::MEDIUM, (string) $this->text->getSize());
        $this->assertTrue($this->text->getNullable());
    }

    public function testSetSize()
    {
        $return = $this->text->setSize(Size::long());
        $this->assertEquals(Size::LONG, (string) $this->text->getSize());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Text', $return);
    }

    public function testSetNullable()
    {
        $return = $this->text->setNullable(true);
        $this->assertTrue($this->text->getNullable());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Text', $return);
    }
}
