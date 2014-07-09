<?php

/**
 * Create table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl;

use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Ddl\Constraint\Primary;

/**
 * Create table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to create a Create Table query
     * @test
     */
    public function createTableQuery()
    {
        $ddl = new CreateTable('users');
        $ddl->addColumn(new Integer('id', ['autoIncrement' => true, 'size' => Size::long()]))
            ->addColumn(new Text('name', ['size' => Size::tiny()]))
            ->addColumn(new Varchar('username', 255))
            ->addColumn(new Varchar('password', 128))
            ->addConstraint(new Primary('usersPk', ['columnNames' => ['id']]))
            ->addConstraint(new Unique('usernameUnique', ['column' => 'username']));

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

    /**
     * Trying to retrieve the SQL from create table object
     * @test
     */
    public function retrieveTheSqlForCreateTable()
    {
       $expected = "CREATE TABLE users";
    }
}
