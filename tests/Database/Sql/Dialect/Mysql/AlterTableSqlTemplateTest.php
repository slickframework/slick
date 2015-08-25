<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Mysql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Dialect\Mysql\AlterTableSqlTemplate;

/**
 * Alter Table Sql Template Test case
 *
 * @package Slick\Tests\Database\Sql\Dialect\Mysql
 */
class AlterTableSqlTemplateTest extends TestCase
{

    public function testAlterTableQuery()
    {
        $template = new AlterTableSqlTemplate();
        $sql = new AlterTable('users');
        $sql->addColumn(new Column\Text('test'))
            ->addColumn(new Column\DateTime('birthDate'))
            ->dropColumn(new Column\Text('age'))
            ->changeColumn(
                new Column\Integer(
                    'level',
                    ['size' => Column\Size::tiny(), 'default' => 4]
                )
            )
            ->dropConstraint(
                new Constraint\ForeignKey('usersFk', 'user_id', 'users', 'id')
            )
            ->dropConstraint(
                new Constraint\Primary('pk')
            )
            ->dropConstraint(new Constraint\Unique('username'))
            ->addConstraint(
                new Constraint\Primary('peoplePrimary', ['columnNames' => ['id']])
            );
        $expected =
            "ALTER TABLE users DROP FOREIGN KEY usersFk, DROP PRIMARY KEY, DROP INDEX username;".
            "ALTER TABLE users ADD (test TEXT NOT NULL, birthDate DATETIME NOT NULL);".
            "ALTER TABLE users CHANGE COLUMN level level SMALLINT NOT NULL DEFAULT 4;".
            "ALTER TABLE users DROP COLUMN age;".
            "ALTER TABLE users ADD (CONSTRAINT peoplePrimary PRIMARY KEY (id))";
        $this->assertEquals(
            $expected,
            $template->processSql($sql)
        );
    }
}
