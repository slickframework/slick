<?php

/**
 * Mysql create database functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Mysql;

use Slick\Configuration\Driver\Ini;
use Slick\Database\Adapter\MysqlAdapter;
use Slick\Database\Adapter;
use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\Column\Blob;
use Slick\Database\Sql\Ddl\Column\Boolean;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Ddl\DropTable;

/**
 * Mysql create database functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateDatabaseTest extends \Codeception\TestCase\Test
{
    /**
     * @var MysqlAdapter
     */
    protected $_adapter;

    /**
     * Initialize the Mysql adapter
     */
    protected function _before()
    {
        parent::_before();
        $cfg = new Ini(['file' => __DIR__ .'/database.ini']);
        $adapter = new Adapter($cfg->get('default'));
        /** @var Adapter\MysqlAdapter $mysql */
        $this->_adapter = $adapter->initialize();
    }

    /**
     * Cleans up every thing for next test
     */
    protected function _after()
    {
        $this->_adapter->execute('drop table if exists comments;
            drop table if exists posts_tags;
            drop table if exists posts;
            drop table if exists tags;
            drop table if exists profiles;
            drop table if exists credentials;
            drop table if exists people;
        ');
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Create a database based on a schema object
     * @test
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
        $alter->changeColumn(new Varchar('timeZone', 40))
            ->dropColumn(new Text('language'))
            ->addColumn(new Boolean('active'))
            ->changeColumn(new Blob('picture', 1024))
            ->addColumn(new Text('created'))
            ->addColumn(new Text('updated'));
        $result = $alter->execute();
        $this->assertTrue($result == 0);

        $alter = new AlterTable('tags');
        $alter->setAdapter($this->_adapter);
        $alter->dropColumn(new Text('created'))
            ->dropColumn(new Text('updated'));

        $result = $alter->execute();
        $this->assertTrue($result == 0);

        $alter = new AlterTable('people');
        $alter->setAdapter($this->_adapter);
        $alter->addColumn(new Varchar('test', 64))
            ->addConstraint(new Unique('peopleUniqueTest', ['column' => 'test']));
        $result = $alter->execute();
        $this->assertTrue($result == 0);

        $alter = new AlterTable('credentials');
        $alter->setAdapter($this->_adapter);
        $alter->changeColumn(new Integer('id', ['autoIncrement' => false, 'size' => Size::big()]))
            ->execute();
        $alter->dropConstraint(new Unique('credentialsUniqueUsername'))
            ->dropConstraint(new Primary('credentialsPrimary'))
            ->dropConstraint(new ForeignKey('credentialPersonFk', '', '', ''));

        $result = $alter->execute();
        $this->assertTrue($result == 0);

        $drop = new DropTable('comments');
        $drop->setAdapter($this->_adapter);
        $result = $drop->execute();
        $this->assertTrue($result == 0);

        $loader = new Schema\Loader(['adapter' => $this->_adapter]);
        $schema = $loader->load();

        $this->assertEquals(6, count($schema->getTables()));
        $column = $schema->getTables()['profiles']->getColumns()['picture'];
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Blob', $column);

        $col = $schema->getTables()['profiles']->getColumns('updated');
        $this->assertTrue(isset($col));
    }
}