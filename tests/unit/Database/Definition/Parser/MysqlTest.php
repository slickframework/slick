<?php

/**
 * Mysql parser test case
 *
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Definition\Parser;

use Codeception\Util\Stub;
use Slick\Database\RecordList,
    Slick\Database\Definition\Parser\Mysql,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey;

/**
 * Mysql parser test case
 *
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    protected $_mysql;

    protected function _before()
    {
        $data = new \StdClass();
        $data->Table = 'users';
        $prop = 'Create Table';
        $data->$prop = <<<EOS
CREATE TABLE `users` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL COMMENT 'Test',
 `full_name` tinytext,
 `active` tinyint(1) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 UNIQUE KEY `name_idx` (`name`),
 KEY `author_fk_idx` (`author_id`),
 FULLTEXT KEY `name_ft` (`name`),
 CONSTRAINT `author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
 CONSTRAINT `profile` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1
EOS;
        $recordList = new RecordList(array($data));
        $this->_mysql = new Mysql(array('data' => $recordList));
    }

    protected function _after()
    {
        unset($this->_mysql);
    }

    /**
     * Retrieve columns
     * @test
     */
    public function retrieveColumns()
    {
        $columns = $this->_mysql->getColumns();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $columns);
        $id = $columns->findByName('id');
        $this->assertTrue($id->isPrimaryKey());
    }

    /**
     * Retrieve indexes
     * @test
     */
    public function retrieveIndexes()
    {
        $indexes = $this->_mysql->getIndexes();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $indexes);
        $name = $indexes->findByName('name_idx');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $name);
        $this->assertEquals(Index::UNIQUE, $name->getType());
        $this->assertEquals(
            Index::FULLTEXT,
            $indexes->findByName('name_ft')->getType()
        );
        $this->assertEquals(
            Index::INDEX,
            $indexes->findByName('author_fk_idx')->getType()
        );
        $this->assertEquals(array('name'), $indexes->findByName('name_ft')->getIndexColumns());
    }

    /**
     * Retrieve foreign keys (constraints)
     * @test
     */
    public function retrieveForeignKeys()
    {
        $constraints = $this->_mysql->getForeignKeys();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $constraints);
        $author = $constraints->findByName('author');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ForeignKey', $author);
        $this->assertEquals('users', $author->getReferencedTable());
        $this->assertEquals(array('author_id' => 'id'), $author->getIndexColumns());
        $this->assertEquals(ForeignKey::NO_ACTION, $author->onUpdate);
        $this->assertEquals(ForeignKey::SET_NULL, $author->onDelete);

        $profile = $constraints->findByName('profile');
        $this->assertEquals('profiles', $profile->getReferencedTable());
        $this->assertEquals(array('profile_id' => 'id'), $profile->getIndexColumns());
        $this->assertEquals(ForeignKey::RESTRICT, $profile->onUpdate);
        $this->assertEquals(ForeignKey::CASCADE, $profile->onDelete);
    }

}