<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\Column\Blob;
use Slick\Database\Sql\Ddl\Column\Boolean;
use Slick\Database\Sql\Ddl\Column\DateTime;
use Slick\Database\Sql\Ddl\Column\Decimal;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * CreateTable Test case
 *
 * @package Slick\Tests\Database\Sql\Ddl
 */
class CreateTableTest extends TestCase
{

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Prepares for test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->adapter = new CustomAdapter();
    }

    /**
     * Cleans for next test
     */
    protected function tearDown()
    {
        unset($this->adapter);
        parent::tearDown();
    }

    /**
     * Trying to retrieve the SQL from create table object
     * @test
     */
    public function retrieveTheSqlForCreateTable()
    {
        $expected  = "CREATE" . " TABLE users (";
        $expected .= "id BIGINT NOT NULL AUTO_INCREMENT, ";
        $expected .= "age SMALLINT(5), ";
        $expected .= "points INTEGER NOT NULL DEFAULT 100, ";
        $expected .= "name VARCHAR(255) NOT NULL, ";
        $expected .= "username VARCHAR(255) NOT NULL, ";
        $expected .= "password VARCHAR(128) NOT NULL, ";
        $expected .= "active BOOLEAN, ";
        $expected .= "rate FLOAT(3), ";
        $expected .= "score DECIMAL(3, 2), ";
        $expected .= "created TIMESTAMP NOT NULL, ";
        $expected .= "updated TIMESTAMP, ";
        $expected .= "picture BLOB(1024) NOT NULL, ";
        $expected .= "CONSTRAINT pkUsers PRIMARY KEY (id), ";
        $expected .= "CONSTRAINT usernameUnique UNIQUE (username), ";
        $expected .= "CONSTRAINT fkProfile FOREIGN KEY (name) REFERENCES profile(id) ";
        $expected .= "ON DELETE CASCADE ON UPDATE NO ACTION";
        $expected .= ")";
        /** @var CreateTable $ddl */
        $ddl = new CreateTable('users');
        $ddl->addColumn(
            new Integer('id',
                ['autoIncrement' => true, 'size' => Size::big()]
            )
        )
            ->addColumn(
                new Integer('age',
                    ['size' => Size::tiny(), 'length' => 5, 'nullable' => true]
                )
            )
            ->addColumn(new Integer('points', ['default' => 100]))
            ->addColumn(new Text('name', ['size' => Size::tiny()]))
            ->addColumn(new Varchar('username', 255))
            ->addColumn(new Varchar('password', 128))
            ->addColumn(new Boolean('active'))
            ->addColumn(new Decimal('rate', 3))
            ->addColumn(new Decimal('score', 3, 2))
            ->addColumn(new DateTime('created'))
            ->addColumn(new DateTime('updated', ['nullable' => true]))
            ->addColumn(new Blob('picture', 1024))
            ->addConstraint(new Primary('pkUsers', ['columnNames' => ['id']]))
            ->addConstraint(new Unique('usernameUnique', ['column' => 'username']))
            ->addConstraint(
                new ForeignKey(
                    'fkProfile',
                    'name',
                    'profile',
                    'id',
                    ['onDelete' => ForeignKey::CASCADE]
                )
            );
        $ddl->setAdapter($this->adapter);
        $this->assertEquals($expected, $ddl->getQueryString());
    }

    /**
     * Trying to create a Create Table query
     * @test
     */
    public function createTableQuery()
    {
        $ddl = new CreateTable('users');
        $columns = [
            new Integer('id', ['autoIncrement' => true, 'size' => Size::long()]),
            new Text('name', ['size' => Size::tiny()]),
            new Varchar('username', 255),
            new Varchar('password', 128),
        ];
        $constraints = [
            new Primary('usersPk', ['columnNames' => ['id']]),
            new Unique('usernameUnique', ['column' => 'username'])
        ];

        $ddl->setColumns($columns)->setConstraints($constraints);

        $this->assertInstanceOf('Slick\Database\Sql\Ddl\CreateTable', $ddl);
        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $ddl);
        /** @var Primary[]|Unique[] $constraints */
        $constraints = $ddl->getConstraints();
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Constraint\Unique',
            $constraints['usernameUnique']
        );
        $this->assertEquals('username', $constraints['usernameUnique']->getColumn());
        /** @var Integer[]|Text[]|Varchar[] $columns */
        $columns = $ddl->getColumns();
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Text',
            $columns['name']
        );
        $this->assertEquals(128, $columns['password']->getLength());
    }
}
