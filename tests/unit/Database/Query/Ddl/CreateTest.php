<?php

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;
use Codeception\Util\Stub,
    Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey;

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slcik\Database\Query\Ddl\Create
     */
    protected $_create;

    protected $_query;

    protected static $_lastQuery;
    protected static $_usedParams = array();

    /**
     * Set up SUT for tests
     */
    protected function _before()
    {
        $db = new Database(array('type' => 'sqlite'));
        $db = $db->initialize();
        $this->_query = $db->connect()->ddlQuery();
        $this->_create = $this->_query->create('users');
        unset($db);
    }

    protected function _after()
    {
        unset ($this->_query, $this->_create);
    }

    /**
     * Create a new statement
     * @test
     */
    public function retreiveCreateQuery()
    {
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $this->_create);
    }

    /**
     * Add columns to the create definition
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function addColumns()
    {
        $this->_create
            ->addColumn(
                'id',
                array(
                    'autoIncrement' => true,
                    'primaryKey' => true,
                    'type' => Column::TYPE_INTEGER,
                    'unsigned' => true,
                    'description' => 'Users primary key'
                )
            )
            ->addColumn(
                'name',
                array(
                    'notNull' => true,
                    'type' => Column::TYPE_TEXT,
                    'size' => Column::SIZE_SMALL
                )
            )
            ->addColumn(
                'active',
                array(
                    'type' => Column::TYPE_BOOLEAN,
                    'default' => 1,
                    'description' => 'The user affilate state'
                )
            );

        $columns = $this->_create->getColumns();

        $this->assertTrue(
            $columns->contains(
                new Column(
                    array(
                        'notNull' => true,
                        'type' => Column::TYPE_TEXT,
                        'size' => Column::SIZE_SMALL,
                        'name' => 'name'
                    )
                )
            )
        );

        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $columns);
        $this->assertEquals(Column::SIZE_SMALL, $columns[1]->size);

        $this->_create
            ->addColumn(
                'other',
                array(
                    'type' => 'text'
                )
            );
    }

    /**
     * Try to add foreign keys to the create statement
     * @test
     */
    public function addForeignKeys()
    {
        $expected = array(
            'name' => 'fk_profile',
            'referencedTable' => 'profile',
            'indexColumns' => array('profile_id' => 'id'),
            'onUpdate' => ForeignKey::NO_ACTION,
            'onDelete' => ForeignKey::CASCADE
        );
        $result = $this->_create->addForeignKey($expected);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $result);

        $fk = new ForeignKey($expected);
        $fk->setName('fk_author')->setReferencedTable('users')
            ->setIndexColumns(array())
            ->addIndexColumn('author_id', 'id')
            ->setOnDelete(ForeignKey::NO_ACTION);

        $stm = $this->_create->addForeignKey($fk);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $stm);
        $fks = $this->_create->getForeignKeys();
        $this->assertEquals('profile', $fks[0]->referencedTable);
        $this->assertEquals(array('author_id' => 'id'), $fks[1]->indexColumns);
    }

    /**
     * Add indexes to create statement
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function addIndexes()
    {
        $result = $this->_create->addIndex('name', array('type' => Index::FULLTEXT));
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $result);
        $idx = new Index(array('name' => 'dob_idx', 'indexColumns' => array('dob')));
        $result->addIndex($idx);
        $indexes = $result->getIndexes();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $indexes[0]);
        $this->assertEquals($idx, $indexes[1]);
        $result->addIndex(null);
    }

    /**
     * Add creation table options
     * @test
     */
    public function addTableOptions()
    {
        $expected = array('ENGINE' => 'InnoDB');
        $result = $this->_create->addOption('ENGINE', 'InnoDB');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $result);
        $this->assertEquals($expected, $result->getOptions());
    }

    /**
     * Check the dialect conversion
     * @test
     */
    public function checkCreateSql()
    {
        $this->_mockQuery();
        $this->_create
            ->addColumn(
                'id',
                array(
                    'primaryKey' => true,
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'autoIncrement' => true,
                    'size' => Column::SIZE_BIG
                )
            )
            ->addColumn(
                'username',
                array(
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 255,
                    'notNull' => true
                )
            )
            ->addColumn(
            	'fullName',
            	array(
            		'type' => Column::TYPE_TEXT,
            		'notNull' => true
            	)
            )
            ->addColumn(
                'active',
                array(
                    'type' => Column::TYPE_BOOLEAN,
                    'default' => 1,
                    'notNull' => true
                )
            )
            ->addColumn(
            	'cardId',
            	array(
            		'type' => Column::TYPE_INTEGER,
            		'size' => Column::SIZE_SMALL,
            		'zeroFill' => TRUE
            	)
            )
            ->addColumn(
            	'age',
            	array(
            		'type' => Column::TYPE_INTEGER,
            		'notNull' => true
            	)
            )
            ->addColumn(
            	'price',
            	array(
            		'type' => Column::TYPE_FLOAT,
            		'notNull' => true,
            		'default' => '2.30'
            	)
            )
            ->addColumn(
                'picture',
                array(
                    'type' => Column::TYPE_BLOB,
                    'size' => Column::SIZE_MEDIUM
                )
            )
            ->addColumn(
                'created',
                array(
                    'type' => Column::TYPE_DATETIME,
                    'notNull' => true,
                    'description' => 'The criation date and time'
                )
            )
            ->addColumn(
                'author_id',
                array(
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
                    'size' => Column::SIZE_BIG
                )
            )
            ->addIndex('username', array('type' => Index::UNIQUE, 'storageType' => Index::STORAGE_RTREE))
            ->addIndex('fullName', array('type' => Index::FULLTEXT))
            ->addIndex('age', array('storageType' => Index::STORAGE_BTREE))
            ->addIndex('created', array('storageType' => Index::STORAGE_HASH))
            ->addForeignKey(
                array(
                    'name' => 'author_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('author_id' => 'id'),
                    'onDelete' => ForeignKey::SET_NULL
                )
            );

        $this->_create->execute();
        $expected = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `fullName` TEXT NOT NULL,
    `active` BOOLEAN NOT NULL DEFAULT '1',
    `cardId` TINYINT ZEROFILL NULL,
    `age` INT NOT NULL,
    `price` FLOAT NOT NULL DEFAULT '2.30',
    `picture` MEDIUMBLOB NULL,
    `created` DATETIME NOT NULL COMMENT 'The criation date and time',
    `author_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `username_idx` USING RTREE (`username` ASC),
    FULLTEXT INDEX `fullName_idx` (`fullName` ASC),
    INDEX `age_idx` USING BTREE (`age` ASC),
    INDEX `created_idx` USING HASH (`created` ASC),
    CONSTRAINT `author_fk`
        FOREIGN KEY (`author_id`)
        REFERENCES `users` (`id`)
        ON DELETE SET NULL
        ON UPDATE NO ACTION
)
EOS;
        $this->assertEquals(trim($expected), trim(self::$_lastQuery));
    }

    /**
     * Create a simple table to test null responses
     * 
     * @test
     */
    public function crateSimpleTable()
    {
    	$this->_mockQuery();
    	$this->_create
    		->addColumn(
    			'id',
    			array(
                    'type' => Column::TYPE_INTEGER,
                    'notNull' => true,
    			)
    		)
    		->addColumn(
    			'name',
    			array(
    				'type' => Column::TYPE_TEXT,
    				'size' => Column::SIZE_SMALL
    			)
    		)
    		->addOption('ENGINE', 'InnoDB');
    	$this->_create->execute();
        $expected = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL,
    `name` TINYTEXT NULL
)
ENGINE = 'InnoDB'
EOS;
        $this->assertEquals(trim($expected), trim(self::$_lastQuery));
    }

    /**
     * Check all constraints
     * @test
     */
    public function checkAllConstraints()
    {
    	$this->_mockQuery();

    	$this->_create
    		->addColumn(
    			'user_id',
    			array(
    				'type' => Column::TYPE_INTEGER,
    				'notNull' => true
    			)
    		)
    		->addColumn(
    			'profile_id',
    			array(
    				'type' => Column::TYPE_INTEGER,
    				'notNull' => true
    			)
    		)
    		->addColumn(
    			'account_id',
    			array(
    				'type' => Column::TYPE_INTEGER,
    				'notNull' => true
    			)
    		)
    		->addForeignKey(
                array(
                    'name' => 'user_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('user_id' => 'id'),
                    'onDelete' => ForeignKey::CASCADE,
                    'onUpdate' => ForeignKey::SET_NULL
                )
            )
            ->addForeignKey(
                array(
                    'name' => 'profile_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('profile_id' => 'id'),
                    'onDelete' => ForeignKey::RESTRICT,
                    'onUpdate' => ForeignKey::RESTRICT
                )
            )
            ->addForeignKey(
                array(
                    'name' => 'account_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('account_id' => 'id'),
                    'onDelete' => ForeignKey::NO_ACTION,
                    'onUpdate' => ForeignKey::CASCADE
                )
            );

        $this->_create->execute();
        $expected = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT NOT NULL,
    `profile_id` INT NOT NULL,
    `account_id` INT NOT NULL,
    CONSTRAINT `user_fk`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE
        ON UPDATE SET NULL,
    CONSTRAINT `profile_fk`
        FOREIGN KEY (`profile_id`)
        REFERENCES `users` (`id`)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,
    CONSTRAINT `account_fk`
        FOREIGN KEY (`account_id`)
        REFERENCES `users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE CASCADE
)
EOS;
        $this->assertEquals(trim($expected), trim(self::$_lastQuery));
    }

    protected function _mockQuery()
    {
    	$query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'Mysql',
                    'connector' => $this->_create->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    self::$_usedParams = $params;
                    return true;
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_create->setQuery($query);
    }

}