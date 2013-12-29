<?php

/**
 * SQLite Create test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Sql\Dialect;

use Codeception\Util\Stub,
    Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey;

/**
 * SQLite Create test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLiteCreateTest extends \Codeception\TestCase\Test
{
    
    /**
     * Create table with SQLite
     * @test
     */
    public function createTable()
    {
        $db = new Database(array('type' => 'sqlite'));
        $conn = $db->initialize();
        $query = $conn->connect()->ddlQuery();
        $query->create('users')
            ->addColumn(
                'id',
                array(
                    'primaryKey' => true,
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => Column::SIZE_NORMAL
                )
            )
            ->addColumn(
                'username',
                array(
                    'type' => Column::TYPE_VARCHAR,
                    'length' => 255,
                    'notNull' => true
                )
            )
            ->addColumn(
                'author_id',
                array(
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => Column::SIZE_MEDIUM
                )
            )
            ->addIndex(
                'username',
                array(
                    'type' => Index::UNIQUE,
                    'storageType' => Index::STORAGE_RTREE
                )
            )
            ->addForeignKey(
                array(
                    'name' => 'author_fk',
                    'indexColumns' => array('author_id' => 'id'),
                    'referencedTable' => 'users',
                    'onDelete' => ForeignKey::SET_NULL
                )
            )
            ->execute();
        $expected = array();
        $expected[] = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `author_id` MEDIUMINT NOT NULL,
    CONSTRAINT `author_fk`
        FOREIGN KEY (`author_id`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE NO ACTION
)
EOS;
        $expected[] = "CREATE UNIQUE INDEX `username_idx` ON users (`username` ASC)";
        $this->assertEquals($expected, $query->sql);
    }

    /**
     * Create a simple table
     * @test
     */
    public function createSimpleTable()
    {
        $db = new Database(array('type' => 'sqlite'));
        $conn = $db->initialize();
        $query = $conn->connect()->ddlQuery();
        $query->create('users')
            ->addColumn(
                'id',
                array(
                    'primaryKey' => true,
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => Column::SIZE_NORMAL
                )
            )
            ->addColumn(
                'username',
                array(
                    'type' => Column::TYPE_VARCHAR,
                    'length' => 255,
                    'notNull' => true,
                    'description' => 'Some text'
                )
            )
            ->addColumn(
                'author_id',
                array(
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => Column::SIZE_MEDIUM,
                    'default' => '1'
                )
            )
            
            ->addForeignKey(
                array(
                    'name' => 'author_fk',
                    'indexColumns' => array('author_id' => 'id'),
                    'referencedTable' => 'users',
                    'onDelete' => ForeignKey::SET_NULL
                )
            )
            ->execute();

        $expected = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `author_id` MEDIUMINT NOT NULL DEFAULT '1',
    CONSTRAINT `author_fk`
        FOREIGN KEY (`author_id`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE NO ACTION
)
EOS;
            $this->assertEquals($expected, $query->sql);
    }

}