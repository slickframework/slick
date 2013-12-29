<?php

/**
 * Index test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl\Utility;

use Codeception\Util\Stub,
    Slick\Database\Query\Ddl\Utility\Index;

/**
 * Index test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class IndexTest extends \Codeception\TestCase\Test
{

    /**
     * Create an index
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function createIndex()
    {
        $idx = new Index(array('name' => 'name_idx', 'indexColumns' => array('name')));
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $idx);
        $result = $idx->addColumn('id');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $result);
        $this->assertEquals(array('name', 'id'), $idx->getIndexColumns());
        $result = $idx->setType(Index::UNIQUE);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $result);
        $this->assertEquals(Index::UNIQUE, $idx->type);
        $idx->setType('UNIQUE');
    }

    /**
     * Set the storage type
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function setStorageType()
    {
        $idx = new Index();
        $result = $idx->setStorageType(Index::STORAGE_BTREE);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $result);
        $this->assertEquals(Index::STORAGE_BTREE, $idx->getStorageType());
        $idx->setStorageType('BTREE');
    }

}