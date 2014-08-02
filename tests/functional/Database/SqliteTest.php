<?php

/**
 * Sqlite functional test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database;

use Slick\Database\Adapter;
use Slick\Database\Adapter\SqliteAdapter;
use Slick\Database\Sql\Ddl\Column\Blob;
use Slick\Database\Sql\Ddl\Column\Boolean;
use Slick\Database\Sql\Ddl\Column\DateTime;
use Slick\Database\Sql\Ddl\Column\Float;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Ddl\DropTable;

/**
 * Sqlite functional test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */
class SqliteTest extends \Codeception\TestCase\Test
{

    /**
     * @var SqliteAdapter
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected static $_dbFile = 'sqlite.db';

    /**
     * Runs before each test
     */
    protected function _before()
    {
        parent::_before();
        $adapter = new Adapter(
            [
                'driver' => 'sqlite',
                'options' => [
                    'file' => __DIR__ .'/'. static::$_dbFile
                ]
            ]
        );
        $this->_adapter = $adapter->initialize();
    }

    /**
     * Clean up after each test
     */
    protected function _after()
    {
        unset($this->_adapter);
        unlink(__DIR__ . '/sqlite.db');
        parent::_after();
    }

    /**
     * Create an invalid adapter
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function createInvalidAdapter()
    {
        $adapter = new Adapter(
            [
                'driver' => 'sqlite',
                'options' => [
                    'file' => '/_unknown_/'. static::$_dbFile
                ]
            ]
        );
        $adapter->initialize();
    }

    /**
     * Trying to create a database table
     * @test
     */
    public function createDatabasePosts()
    {
        $this->assertNull($this->_adapter->getSchemaName());
        $ddl = new CreateTable('categories');
        $ddl->setAdapter($this->_adapter);
        $ddl->addColumn(new Integer('id', ['autoIncrement' => 'true', 'size' => Size::big()]))
            ->addColumn(new Varchar('name', 255))
            ->addConstraint(new Primary('tagsPk', ['columnNames' => ['id']]))
            ->addConstraint(new Unique('nameUnique', ['column' => 'name']));

        $ddl->execute();

        $ddl = new CreateTable('posts');
        $ddl->setAdapter($this->_adapter);
        $ddl->addColumn(new Integer('id', ['autoIncrement' => 'true', 'size' => Size::big()]))
            ->addColumn(new Varchar('title', 255))
            ->addColumn(new Text('body', ['size' => Size::big()]))
            ->addColumn(new DateTime('crated'))
            ->addColumn(new Blob('photo', 2048))
            ->addColumn(new Boolean('active'))
            ->addColumn(new Integer('age', ['size' => Size::tiny(), 'default' => 1]))
            ->addColumn(new Integer('category_id', ['size' => Size::big()]))
            ->addColumn(new Float('score', 3, 2));
        $ddl->addConstraint(new Primary('postsPk', ['columnNames' => ['id']]))
            ->addConstraint(new ForeignKey('categoryFk', 'category_id', 'categories', 'id'));

        $result = $ddl->execute();
        $this->assertEquals(0, $result);


    }
}
