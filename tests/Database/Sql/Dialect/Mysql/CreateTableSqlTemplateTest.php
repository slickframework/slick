<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Dialect\Mysql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Dialect\Mysql\CreateTableSqlTemplate;

/**
 * Class CreateTableSqlTemplateTest
 *
 * @package Slick\Tests\Database\Sql\Dialect\Mysql
 */
class CreateTableSqlTemplateTest extends TestCase
{

    public function testCreateTableSql()
    {
        $template = new CreateTableSqlTemplate();
        $sql = new CreateTable('people');
        $sql->addColumn(new Column\Text('name', ['size' => Column\Size::tiny()]))
            ->addColumn(new Column\Text('description', ['size' => Column\Size::long()]));
        $expected = "CREATE TABLE people (".
            "name TINYTEXT NOT NULL, ".
            "description LONGTEXT NOT NULL)";
        $this->assertEquals($expected, $template->processSql($sql));
    }
}
