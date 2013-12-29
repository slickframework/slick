<?php

/**
 * Column
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Column - A database column definition
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Column extends Base implements TableElementInterface
{

    /**#@+
     * @const string TYPE for data type constants
     */
    const TYPE_INTEGER  = 0;
    const TYPE_TEXT     = 1;
    const TYPE_FLOAT    = 2;
    const TYPE_VARCHAR  = 3;
    const TYPE_BLOB     = 4;
    const TYPE_BOOLEAN  = 5;
    const TYPE_DATETIME = 6;
    /**#@-*/

    /**#@+
     * @const string SIZE for data size constants
     */
    const SIZE_SMALL  = 'SMALL';
    const SIZE_NORMAL = 'NORMAL';
    const SIZE_MEDIUM = 'MEDIUM';
    const SIZE_BIG    = 'BIG';
    /**#@-*/

    /**
     * @readwrite
     * @var string The column name
     */
    protected $_name;

    /**
     * @readwrite
     * @var string Column Data type
     */
    protected $_type = self::TYPE_TEXT;

    /**
     * @readwrite
     * @var string|integer The column data size
     */
    protected $_size = self::SIZE_NORMAL;

    /**
     * @readwrite
     * @var integer The column length
     */
    protected $_length = 0;

    /**
     * @readwrite
     * @var boolean The is this a primary key
     */
    protected $_primaryKey = false;

    /**
     * @readwrite
     * @var boolean Is not null by default
     */
    protected $_notNull = false;

    /**
     * @readwrite
     * @var boolean Use unsigned binary on integers
     */
    protected $_unsigned = false;

    /**
     * @readwrite
     * @var boolean Zero padding fill
     */
    protected $_zeroFill = false;

    /**
     * @readwrite
     * @var boolean Is automaticlly incremented
     */
    protected $_autoIncrement = false;

    /**
     * @readwrite
     * @var string Default value
     */
    protected $_default = null;

    /**
     * @readwrite
     * @var string The column description
     */
    protected $_description;

    /**
     * Adds the type for this column
     *
     * You should use the Column::TYPE_ class constants to set the data type.
     * This way you can ensure the dialect will know the exactly type for the
     * SQL dialect you use.
     * 
     * @param integer $type The data type for this column
     *
     * @return \Slick\Database\Query\Ddl\Utility\Column A self instance for
     *  method call chains.
     *
     * @throws \Slick\Database\ExceptionInvalidArgumentException If Type is an
     *  unknown (out of define constants) type.
     */
    public function setType($type)
    {
        // Out of defined constants
        if (!is_int($type) || $type < 0 || $type > 6) {
            throw new Exception\InvalidArgumentException(
                "Unknown column type used. Use one of Column::TYPE_ " .
                "constants to define a column data type"
            );
        }

        if ($type == self::TYPE_BOOLEAN) {
            $this->_length = 1;
        } else {
            $this->_length = 0;
        }
        $this->_type = $type;
        return $this;
    }

    /**
     * Returns a string version of this column
     * 
     * @return string
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function __toString()
    {
        $type = $this->typeAsString();
        $str  = "'{$this->name}' {$type}";
        $str .= ($this->_length > 0) ? "({$this->_length})" : "";
        $str .= " {$this->size}";
        $str .= ($this->isPrimaryKey()) ? " PRIMARY KEY" :  "";
        $str .= ($this->isNotNull()) ? " NOT NULL" :  " NULL";
        $str .= ($this->isUnsigned()) ? " UNSIGNED" :  "";
        $str .= ($this->isAutoIncrement()) ? " AUTO INCREMENT" :  "";
        $str .= (!is_null($this->default)) ?
            " DEFAULT '{$this->default}'" :  "";
        $str .= (!is_null($this->description)) ?
            " DESCRIPTION '{$this->description}'" :  "";
        return $str;
    }

    /**
     * Returns the name of the type for this column
     * 
     * @return string
     */
    public function typeAsString()
    {
        switch ($this->_type) {
            case self::TYPE_INTEGER:
                $str = 'INTEGER';
                break;

            case self::TYPE_FLOAT:
                $str = 'FLOAT';
                break;

            case self::TYPE_VARCHAR:
                $str = 'VARCHAR';
                break;

            case self::TYPE_BLOB:
                $str = 'BLOB';
                break;

            case self::TYPE_BOOLEAN:
                $str = 'BOOLEAN';
                break;

            case self::TYPE_DATETIME:
                $str = 'DATETIME';
                break;

            case self::TYPE_TEXT:
            default:
                $str = 'TEXT';
        }
        return $str;
    }
}