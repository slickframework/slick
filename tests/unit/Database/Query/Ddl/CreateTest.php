<?php

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;
use Codeception\Util\Stub,
    Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\ForeignKey;

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slcik\Database\Query\Ddl\Create
     */
    protected $_create;

    protected $_query;

    /**
     * Set up SUT for tests
     */
    protected function _before()
    {
        $db = new Database(array('type' => 'sqlite'));
        $db = $db->initialize();
        $this->_query = $db->connect()->ddlQuery();
        $this->_create = $this->_query->create('users');
        unset($db);
    }

    protected function _after()
    {
        unset ($this->_query, $this->_create);
    }

    /**
     * Create a new statement
     * @test
     */
    public function retreiveCreateQuery()
    {
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $this->_create);
    }

    /**
     * Add columns to the create definition
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function addColumns()
    {
        $this->_create
            ->addColumn(
                'id',
                array(
                    'autoIncrement' => true,
                    'primaryKey' => true,
                    'type' => Column::TYPE_INTEGER,
                    'unsigned' => true,
                    'description' => 'Users primary key'
                )
            )
            ->addColumn(
                'name',
                array(
                    'notNull' => true,
                    'type' => Column::TYPE_TEXT,
                    'size' => Column::SIZE_SMALL
                )
            )
            ->addColumn(
                'active',
                array(
                    'type' => Column::TYPE_BOOLEAN,
                    'default' => 1,
                    'description' => 'The user affilate state'
                )
            );

        $columns = $this->_create->getColumns();

        $this->assertTrue(
            $columns->contains(
                new Column(
                    array(
                        'notNull' => true,
                        'type' => Column::TYPE_TEXT,
                        'size' => Column::SIZE_SMALL,
                        'name' => 'name'
                    )
                )
            )
        );

        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $columns);
        $this->assertEquals(Column::SIZE_SMALL, $columns[1]->size);

        $this->_create
            ->addColumn(
                'other',
                array(
                    'type' => 'text'
                )
            );
    }

    /**
     * Try to add foreign keys to the create statement
     * @test
     */
    public function addForeignKeys()
    {
        $expected = array(
            'name' => 'fk_profile',
            'referencedTable' => 'profile',
            'indexColumns' => array('profile_id' => 'id'),
            'onUpdate' => ForeignKey::NO_ACTION,
            'onDelete' => ForeignKey::CASCADE
        );
        $result = $this->_create->addForeignKey($expected);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $result);

        $fk = new ForeignKey($expected);
        $fk->setName('fk_author')->setReferencedTable('users')
            ->setIndexColumns(array())
            ->addIndexColumn('author_id', 'id')
            ->setOnDelete(ForeignKey::NO_ACTION);

        $stm = $this->_create->addForeignKey($fk);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $stm);
        $fks = $this->_create->getForeignKeys();
        $this->assertEquals('profile', $fks[0]->referencedTable);
        $this->assertEquals(array('author_id' => 'id'), $fks[1]->indexColumns);
    }

}