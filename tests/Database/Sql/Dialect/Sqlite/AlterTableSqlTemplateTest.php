<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Sqlite;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Dialect\Sqlite\AlterTableSqlTemplate;

/**
 * Class AlterTableSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Sqlite
 */
class AlterTableSqlTemplateTest extends TestCase
{

    public function testUnsupportedChangeColumn()
    {
        $sql = new AlterTable('Users');
        $sql->changeColumn(new Integer('age'));
        $this->setExpectedException(
            'Slick\Database\Exception\ServiceException'
        );
        (new AlterTableSqlTemplate())->processSql($sql);
    }

    public function testAlterTableAddColumn()
    {
        $sql = new AlterTable('users');
        $sql->addColumn(new Integer('age'))
            ->addColumn(new Text('name'));
        $expected = 'ALTER TABLE users ADD COLUMN age INTEGER NOT NULL; '.
                    'ALTER TABLE users ADD COLUMN name TEXT NOT NULL';
        $result = (new AlterTableSqlTemplate())->processSql($sql);
        $this->assertEquals($expected, $result);

    }

}
