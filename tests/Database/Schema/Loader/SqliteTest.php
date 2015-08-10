<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Schema\Loader;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter;
use Slick\Database\Schema;

/**
 * Schema SQLite loader test case
 *
 * @package Slick\Tests\Database\Schema\Loader
 */
class SqliteTest extends TestCase
{
    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * Create data base
     */
    protected function setUp()
    {
        parent::setUp();
        $dbFile = __DIR__ .'/myTest.db';
        $adapter = new Adapter([
            'driver' => Adapter::DRIVER_SQLITE,
            'options' => [
                'file' => $dbFile
            ]
        ]);
        /** @var Adapter\MysqlAdapter $mysql */
        $this->adapter = $adapter->initialize();

        /** @var Schema $schema */
        $schema = include(__DIR__ . '/schemaDef.php');
        $schema->setAdapter($this->adapter);
        $result = $this->adapter->execute($schema->getCreateStatement());
    }

    public function testLoadSchema()
    {
        $loader = new Schema\Loader\Sqlite(
            ['adapter' => $this->adapter]
        );
        $schema = $loader->getSchema();
        $this->assertEquals(7, count($schema->getTables()));
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
    }

    protected function tearDown()
    {
        $dbFile = __DIR__ .'/myTest.db';
        if (is_file($dbFile)) {
            //copy($dbFile, __DIR__ .'/myTest-result.db');
            unlink($dbFile);
        }
        unset($this->adapter);
        parent::tearDown();
    }
}
