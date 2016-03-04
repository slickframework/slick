<?php

/**
 * Entity test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm;

use Slick\Configuration\Configuration;
use Slick\Database\Adapter\SqliteAdapter;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\Sqlite;
use Slick\Database\Sql;
use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Slick\Orm\Entity;
use Slick\Orm\Events\Select;
use Zend\EventManager\SharedEventManager;

/**
 *  Entity test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntityTest extends \Codeception\TestCase\Test
{

    protected function _before()
    {
        parent::_before();
        Configuration::addPath(__DIR__);

        $adapter = new SqliteAdapter(['file' => 'tmp.db']);
        $createTable = new CreateTable('models');
        $createTable->setAdapter($adapter);
        $createTable->addColumn(new Integer('id', ['autoIncrement' => true]))
            ->addColumn(new Text('name'));
        $createTable->execute();

        Sql::createSql($adapter)->insert('models')->set(['name' => 'PHP'])->execute();
        //$adapter->execute("PRAGMA integrity_check");
    }

    protected function _after()
    {
        $adapter = new SqliteAdapter(['file' => 'tmp.db']);
        $drop = new Sql\Ddl\DropTable('models');
        $drop->setAdapter($adapter)->execute();
        if (file_exists('tmp.db')) {
            unlink('tmp.db');
        }
    }


    /**
     * Create an entity
     * @test
     */
    public function createEntity()
    {
        $model = new Model();
        $this->assertEquals('models', $model->getTableName());

        $adapter = $model->getAdapter();
        $this->assertInstanceOf('Slick\Database\Adapter\AdapterInterface', $adapter);

        $this->assertSame($adapter, $model->getAdapter());

        $container = ContainerBuilder::buildContainer([
            'sharedEventManager' => Definition::object(
                    'Zend\EventManager\SharedEventManager'
                )
        ]);

        /** @var SharedEventManager $eventManager */
        $eventManager = $container->get('sharedEventManager');

        $eventManager->attach('Orm\Model', Select::BEFORE_SELECT, function(Select $event) {
            $this->assertInstanceOf('Slick\Database\Sql\Select', $event->sqlQuery);
            $this->assertEquals(Select::GET, $event->action);
        });

        $eventManager->attach('Orm\Model', Select::AFTER_SELECT, function(select $event) {
            $this->assertInstanceOf('Slick\Database\Sql\Select', $event->sqlQuery);
            $this->assertEquals(
                [
                    'id' => 1,
                    'name' => 'PHP'
                ],
                $event->data
            );
        });

        $container->set('sharedEventManager', $eventManager);

        /** @var Model $model */
        $model = Model::get(1);
        $this->assertInstanceOf('Orm\Model', $model);
        $this->assertNull(Model::get(100));
        $this->assertEquals('PHP', $model->name);
    }

}

/**
 * Class Model
 * @package Orm
 *
 * @property integer $id
 * @property string $name
 */
class Model extends Entity
{

    /**
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var string
     */
    protected $_configFile = 'orm-database';

    /**
     * @readwrite
     * @var string
     */
    protected $_configName = 'unit-test';
}