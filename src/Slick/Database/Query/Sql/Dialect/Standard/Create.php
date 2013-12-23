<?php

/**
 * Create
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\Standard;

use Slick\Common\Base,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
    Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Database\Query\Ddl\Utility\Column;

/**
 * Create
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Create extends Base
{

    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;

    /**
     * @read
     * @var string To use in column definition perfix
     */
    protected $_definitionPrefix = '';

    /**
     * @read
     * @var string The Create Table template SQL
     */
    protected $_template = <<<EOS
CREATE TABLE IF NOT EXISTS `<tableName>` (
<definition>
)
<options>
EOS;
    
    protected $_tab = '    ';

    /**
     * Returns the SQL query string for current Select SQL Object
     * 
     * @return String The SQL query string
     */
    public function getStatement()
    {
        return trim(
            str_replace(
                array('<tableName>', '<definition>', '<options>'),
                array(
                    $this->_sql->getTableName(),
                    $this->getDefinitions(),
                    $this->getOptions()
                ),
                $this->_template
            )
        );
    }

    /**
     * Returns all columns, index and constraints definitions
     * 
     * @return string SQL for columns, indexes and constraints
     */
    public function getDefinitions()
    {
        $sql = '';
        $sql .= $this->_getColumns();
        $sql .= $this->_getIndexes();
        $sql .= $this->_getConstraints();
        return $sql;
    }

    /**
     * Returns the table options for creation
     * 
     * @return string Options for create table statement
     */
    public function getOptions()
    {
        $output = '';
        $items = array();
        foreach ($this->_sql->options as $key => $value) {
            $items[] = "{$key} = '{$value}'";
        }
        if (sizeof($items) > 0) {
            $output = implode("\n", $items);
        }
        return $output;
    }

    /**
     * Generates the columns definitions for create table statement
     * 
     * @return string
     */
    protected function _getColumns()
    {
        $columns = $this->_sql->getColumns();
        $items = array();
        foreach ($columns as $column) {
            $str = $this->_definitionPrefix . $this->_getColumnDef($column);
            $str = trim($str);
            $items[] = "{$this->_tab}{$str}";
        }

        return implode(",\n", $items);
    }

    protected function _getColumnDef(Column $column)
    {
        $str  = "`{$column->name}` ";
        $str .= $this->_getColumnType($column);
        
        if ($column->isUnsigned()) {
            $str .= ' UNSIGNED';
        }
        if ($column->isZeroFill()) {
            $str .= ' ZEROFILL';
        }

        if ($column->isNotNull()) {
            $str .= ' NOT NULL';
        } else {
            $str .= ' NULL';
        }

        if ($column->isAutoIncrement()) {
            $str .= ' AUTO_INCREMENT';
        }
        if (strlen($column->default) > 0) {
            $str .= " DEFAULT '{$column->default}'";
        }
        if (strlen($column->description) > 0) {
            $str .= " COMMENT '{$column->description}'";
        }

        if ($column->isPrimaryKey()) {
            $idx = new Index(
                array(
                    'type' => Index::PRIMARY_KEY,
                    'indexColumns' => array($column->name)
                )
            );
            $indexes = $this->_sql->getIndexes()->getArrayCopy();
            array_unshift($indexes, $idx);
            $this->_sql->setIndexes(new ElementList($indexes));
        }
        
        return $str;
    }

    /**
     * Generate Index definitions for create table statement
     * @return string
     */
    protected function _getIndexes($pre = '')
    {
        $indexes = $this->_sql->getIndexes();
        $values = array();
        foreach ($indexes as $index) {
            if ($index->type == Index::PRIMARY_KEY) {
                $values[] = "{$this->_tab}PRIMARY KEY " .
                    "(`{$index->indexColumns[0]}`)";
            } else {
                
                $storage = $this->_getIndexStorage($index);
                $columns = array();

                foreach ($index->getIndexColumns() as $colName) {
                    $columns[] = "`{$colName}` ASC";
                }
                $columns = implode(', ', $columns);
                $prefix = "{$pre}";
                if ($index->type == Index::FULLTEXT) {
                    $prefix = "{$pre}FULLTEXT ";
                }
                if ($index->type == Index::UNIQUE) {
                    $prefix = "{$pre}UNIQUE ";
                }
                $values[] = "{$this->_tab}{$prefix}INDEX `{$index->name}`".
                    "{$storage} ({$columns})";
            }
        }
        if (sizeof($values) > 0)
            return ",\n". implode(",\n", $values);
        return null;
    }
    
    /**
     * Returns the storage type for the provided index
     * 
     * @param Index $index
     * 
     * @return string
     */
    protected function _getIndexStorage(Index $index)
    {
        $storage = null;
        if ($index->getStorageType() != Index::STORAGE_NONE) {
            switch ($index->getStorageType()) {
                case Index::STORAGE_BTREE:
                    $storage = ' USING BTREE';
                    break;
        
                case Index::STORAGE_RTREE:
                    $storage = ' USING RTREE';
                    break;
        
                case Index::STORAGE_HASH:
                    $storage = ' USING HASH';
                    break;
            }
        }
        return $storage;
    }

    /**
     * Generate constraints for foreign keys for this create table statement
     * @return string
     */
    protected function _getConstraints($pre = '')
    {
        $foreignKeys = $this->_sql->getForeignKeys();
        $items = array();
        $template = <<<EOS
    %sCONSTRAINT `%s`
        FOREIGN KEY (`%s`)
        REFERENCES `%s` (`%s`)
        ON DELETE %s
        ON UPDATE %s
EOS;
        foreach ($foreignKeys as $foreignKey) {
            $onDelete = $this->_getOnAction($foreignKey->onDelete);
            $onUpdate = $this->_getOnAction($foreignKey->onUpdate);
            
            $fks = array_keys($foreignKey->indexColumns);
            $items[] = sprintf(
                $template,
                $pre,
                $foreignKey->name,
                $fks[0],
                $foreignKey->referencedTable,
                $foreignKey->indexColumns[$fks[0]],
                $onDelete,
                $onUpdate
            );
        }

        if (sizeof($foreignKeys) > 0)
            return ",\n". implode(",\n", $items);
        return null;

    }
    
    protected function _getOnAction($action)
    {
        $return = null;
        switch ($action) {
            case ForeignKey::RESTRICT:
                $return = 'RESTRICT';
                break;
        
            case ForeignKey::SET_NULL:
                $return = 'SET NULL';
                break;
        
            case ForeignKey::CASCADE:
                $return = 'CASCADE';
                break;
        
            case ForeignKey::NO_ACTION:
            default:
                $return = 'NO ACTION';
        }
        return $return;
    }

    protected function _getColumnType(Column $col)
    {
        switch ($col->getType()) {
            case Column::TYPE_INTEGER:
                $type = $this->_checkSize($col, 'INT');
                break;

            case Column::TYPE_BLOB:
                $type = $this->_checkSize($col, 'BLOB');
                break;

            case Column::TYPE_TEXT:
                $type = $this->_checkSize($col, 'TEXT');
                break;

            case Column::TYPE_DATETIME:
                $type = 'DATETIME';
                break;
            
            case Column::TYPE_BOOLEAN:
                $type = 'BOOLEAN';
                break;

            case Column::TYPE_FLOAT:
                $type = 'FLOAT';
                break;

            case Column::TYPE_VARCHAR:
                $type = "VARCHAR({$col->length})";
                break;

        }

        return $type;
    }

    protected function _checkSize($column, $type)
    {
        switch ($column->getSize()) {
            case Column::SIZE_SMALL:
                $type = "TINY{$type}";
                break;

            case Column::SIZE_MEDIUM:
                $type = "MEDIUM{$type}";
                break;

            case Column::SIZE_BIG:
                $type = "LONG{$type}";
                break;

            case Column::SIZE_NORMAL:
            default:
                $type = "{$type}";
                break;
        }
        if ($type == 'LONGINT') {
            $type = 'BIGINT';
        }

        return $type;
    }
}