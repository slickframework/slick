<?php

/**
 * Sqlite create database functional test case
 *
 * @package   Test\Database\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sqlite;

use Slick\Database\Adapter;
use Slick\Database\Adapter\SqliteAdapter;
use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\Column\Boolean;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\DropTable;

/**
 * Sqlite create database functional test case
 *
 * @package   Test\Database\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateDatabaseTest extends \Codeception\TestCase\Test
{
    /**
     * @var SqliteAdapter
     */
    protected $_adapter;

    /**
     * Initialize the Sqlite adapter
     */
    protected function _before()
    {
        parent::_before();

        $dbFile = __DIR__ .'/myTest.db';
        $adapter = new Adapter([
            'driver' => 'Sqlite',
            'options' => [
                'file' => $dbFile
            ]
        ]);
        /** @var Adapter\MysqlAdapter $mysql */
        $this->_adapter = $adapter->initialize();
    }

    /**
     * Cleans up every thing for next test
     */
    protected function _after()
    {
        $dbFile = __DIR__ .'/myTest.db';
        if (is_file($dbFile)) {
            //copy($dbFile, __DIR__ .'/myTest-result.db');
            unlink($dbFile);
        }
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Create a database based on a schema object
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function createDatabase()
    {
        /** @var Schema $schema */
        $schema = include(__DIR__ . '/schemaDef.php');

        $schema->setAdapter($this->_adapter);
        $result = $this->_adapter->execute($schema->getCreateStatement());
        $this->assertTrue($result == 0);

        $alter = new AlterTable('profiles');
        $alter->setAdapter($this->_adapter);
        $alter->addColumn(new Boolean('active'))
            ->addColumn(new Text('created', ['nullable' => true]))
            ->addColumn(new Text('updated', ['nullable' => true]));
        $result = $alter->execute();
        $this->assertTrue($result == 0);

        $drop = new DropTable('comments');
        $drop->setAdapter($this->_adapter);
        $result = $drop->execute();
        $this->assertTrue($result == 0);

        $loader = new Schema\Loader(['adapter' => $this->_adapter]);
        $schema = $loader->load();
        $this->assertEquals(6, count($schema->getTables()));
        $this->assertEquals(3, count($schema->getTables()['credentials']->getConstraints()));
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Constraint\Primary',
            $schema->getTables()['credentials']->getConstraints()['credentialsPrimary']
        );
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Constraint\ForeignKey',
            $schema->getTables()['credentials']->getConstraints()['credentialsPersonFk']
        );
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Constraint\Unique',
            $schema->getTables()['credentials']->getConstraints()['credentialsUniqueUsername']
        );
        $column = $schema->getTables()['profiles']->getColumns()['picture'];
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $column);

        $col = $schema->getTables()['profiles']->getColumns('updated');
        $this->assertTrue(isset($col));
        $alter = new AlterTable('profiles');
        $alter->setAdapter($this->_adapter);
        $alter->dropColumn(new Text('created'));
        $alter->execute();
    }

}