<?php

/**
 * Alter table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl;

use Slick\Database\Adapter;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Dialect;

/**
 * Alter table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTableTest extends \Codeception\TestCase\Test
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Prepares for test
     */
    protected function _before()
    {
        parent::_before();
        $this->_adapter = new Adapter(
            ['options' => [
                'autoConnect' => false,
                'dialect' => Dialect::STANDARD
            ]]
        );
        $this->_adapter = $this->_adapter->initialize();
    }

    /**
     * Cleans for next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Create an Alter Table query
     * @test
     */
    public function createAlterTableQuery()
    {
        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);
        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $ddl);

        $id = new Integer('id', ['size' => Size::big(), 'autoIncrement' => true]);
        $name = new Text('name', ['size' => Size::small()]);
        $username = new Varchar('username', '255');

        $pk = new Primary('usersPk', ['columnNames' => ['id']]);
        $uniqueName = new Unique('uniqueName', ['column' => 'name']);
        $fkProfile = new ForeignKey('fkProfile', 'profile_id', 'profiles', 'id');

        $alterColumns = ['username' => $username];
        $addColumns = ['id' => $id, 'name' => $name, 'username' => $username];
        $droppedColumns = ['name' => $name, 'username' => $username];

        $addConstraints = ['usersPk' => $pk, 'uniqueName' => $uniqueName, 'fkProfile' => $fkProfile];
        $droppedConstraints = ['fkProfile' => $fkProfile];

        $obj = $ddl->addColumn($id)->addColumn($name)->addColumn($username);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\AlterTable', $obj);

        $obj = $ddl->changeColumn($username);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\AlterTable', $obj);

        $obj = $ddl->dropColumn($name)->dropColumn($username);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\AlterTable', $obj);

        $this->assertEquals($alterColumns, $ddl->getChangedColumns());
        $this->assertEquals($addColumns, $ddl->getColumns());
        $this->assertEquals($droppedColumns, $ddl->getDroppedColumns());

        $obj = $ddl->dropConstraint($fkProfile);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\AlterTable', $obj);

        $obj = $ddl->addConstraint($pk)
            ->addConstraint($uniqueName)
            ->addConstraint($fkProfile);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\AlterTable', $obj);

        $this->assertEquals($droppedConstraints, $ddl->getDroppedConstraints());
        $this->assertEquals($addConstraints, $ddl->getConstraints());
    }

    /**
     * Trying to verify the alter table, add column SQL output
     * @test
     */
    public function verifyAlterTableAddColumnSql()
    {
        $id = new Integer('id', ['size' => Size::big(), 'autoIncrement' => true]);
        $name = new Text('name', ['size' => Size::small()]);
        $username = new Varchar('username', '255');

        $expected  = "ALTER TABLE users ADD (";
        $expected .= "username VARCHAR(255) NOT NULL";
        $end = ")";

        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);
        $ddl->addColumn($username);
        $this->assertEquals($expected.$end, $ddl->getQueryString());

        $expected .= ', ';
        $expected .= "name VARCHAR(255) NOT NULL";
        $ddl->addColumn($name);
        $this->assertEquals($expected.$end, $ddl->getQueryString());
    }

    /**
     * Trying to verify the alter table, modify column SQL output
     * @test
     */
    public function verifyAlterTableModifyColumnSql()
    {
        $id = new Integer('id', ['size' => Size::big(), 'autoIncrement' => true]);
        $name = new Text('name', ['size' => Size::small()]);
        $username = new Varchar('username', '255');

        $expected  = "ALTER TABLE users ALTER COLUMN (";
        $expected .= "username VARCHAR(255) NOT NULL";
        $end = ")";

        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);

        $ddl->changeColumn($username);
        $this->assertEquals($expected.$end, $ddl->getQueryString());

        $expected .= ', ';
        $expected .= "name VARCHAR(255) NOT NULL";
        $ddl->changeColumn($name);
        $this->assertEquals($expected.$end, $ddl->getQueryString());
    }

    /**
     * Trying to verify the alter table, modify column SQL output
     * @test
     */
    public function verifyAlterTableDroppedColumnSql()
    {
        $id = new Integer('id', ['size' => Size::big(), 'autoIncrement' => true]);
        $name = new Text('name', ['size' => Size::small()]);
        $username = new Varchar('username', '255');

        $expected  = "ALTER TABLE users DROP COLUMN (";
        $expected .= "username";
        $end = ")";

        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);

        $ddl->dropColumn($username);
        $this->assertEquals($expected.$end, $ddl->getQueryString());

        $expected .= ', ';
        $expected .= "name";
        $ddl->dropColumn($name);
        $this->assertEquals($expected.$end, $ddl->getQueryString());
    }

    /**
     * Trying to verify the alter table, add constraint SQL output
     * @test
     */
    public function verifyAlterTableAddConstraintSql()
    {
        $pk = new Primary('usersPk', ['columnNames' => ['id']]);
        $uniqueName = new Unique('uniqueName', ['column' => 'name']);

        $expected  = "ALTER TABLE users ADD (";
        $expected .= "CONSTRAINT usersPk PRIMARY KEY (id)";
        $end = ")";

        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);

        $ddl->addConstraint($pk);
        $this->assertEquals($expected.$end, $ddl->getQueryString());

        $expected .= ', ';
        $expected .= "CONSTRAINT uniqueName UNIQUE (name)";
        $ddl->addConstraint($uniqueName);
        $this->assertEquals($expected.$end, $ddl->getQueryString());
    }

    /**
     * Trying to verify the alter table, drop constraint SQL output
     * @test
     */
    public function verifyAlterTableDropConstraintSql()
    {
        $pk = new Primary('usersPk', ['columnNames' => ['id']]);
        $uniqueName = new Unique('uniqueName', ['column' => 'name']);

        $expected  = "ALTER TABLE users DROP CONSTRAINT (";
        $expected .= "usersPk";
        $end = ")";

        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);

        $ddl->dropConstraint($pk);
        $this->assertEquals($expected.$end, $ddl->getQueryString());
    }

    /**
     * Verify multiple changes at once
     * @test
     */
    public function verifyMultipleChangesAtOnce()
    {
        $ddl = new AlterTable('users');
        $ddl->setAdapter($this->_adapter);

        $expected = "ALTER TABLE users DROP CONSTRAINT (uniqueName);" .
            "ALTER TABLE users ADD (id BIGINT NOT NULL AUTO_INCREMENT);" .
            "ALTER TABLE users ALTER COLUMN " .
            "(name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL);" .
            "ALTER TABLE users DROP COLUMN (username);" .
            "ALTER TABLE users ADD (CONSTRAINT usersPk PRIMARY KEY (id), " .
            "CONSTRAINT uniqueName UNIQUE (name))";

        $id = new Integer('id', ['size' => Size::big(), 'autoIncrement' => true]);
        $name = new Text('name', ['size' => Size::small()]);
        $username = new Varchar('username', '255');

        $pk = new Primary('usersPk', ['columnNames' => ['id']]);
        $uniqueName = new Unique('uniqueName', ['column' => 'name']);

        $ddl->addColumn($id)->addConstraint($pk)
            ->changeColumn($name)->changeColumn($username)
            ->dropColumn($username)
            ->addConstraint($uniqueName)
            ->dropConstraint($uniqueName);

        $this->assertEquals($expected, $ddl->getQueryString());
    }
}
