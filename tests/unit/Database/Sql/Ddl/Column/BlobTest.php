<?php

/**
 * Blob column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Column\Blob;

/**
 * Blob column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BlobTest extends \Codeception\TestCase\Test
{
    /**
     * Trying ti create a blob column
     * @test
     */
    public function createBlobColumn()
    {
        $col = new Blob('file', '1024', ['default' => 'test']);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);

        $this->assertEquals('test', $col->getDefault());
        $this->assertFalse($col->getNullable());

        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $col->setNullable(true));
        $this->assertTrue($col->getNullable());

        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $col->setDefault('foo'));
        $this->assertEquals('foo', $col->getDefault());

        $this->assertEquals('1024', $col->getLength());
    }
}
