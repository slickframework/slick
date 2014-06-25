<?php

/**
 * Select join definition
 *
 * @package   Slick\Database\Sql\Select
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Select;

/**
 * Select join definition
 *
 * @package   Slick\Database\Sql\Select
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Join
{
    /**#@+
     * @var string SQL join types
     */
    const JOIN_INNER = 'INNER';
    const JOIN_LEFT = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_FULL = 'FULL OUTER';

    /**
     * @var string Table name
     */
    private $_table;

    /**
     * @var array|string The fields to retrieve
     */
    private $_fields;

    /**
     * @var string The SQL join type
     */
    private $_type;

    /**
     * @var string The join condition
     */
    private $_onClause;

    /**
     * @var string The alias for this table join
     */
    private $_alias;

    /**
     * Creates a join element
     *
     * @param string       $table
     * @param string       $onClause
     * @param string|array $fields
     * @param string       $type
     */
    public function __construct(
        $table, $onClause, $fields=['*'], $type = self::JOIN_LEFT)
    {
        $this->_table = $table;
        $this->_onClause = $onClause;
        $this->_fields = $fields;
        $this->_type = $type;
    }

    /**
     * @param string $type
     *
     * @return Join
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $onClause
     * @return Join
     */
    public function setOnClause($onClause)
    {
        $this->_onClause = $onClause;
        return $this;
    }

    /**
     * @return string
     */
    public function getOnClause()
    {
        return $this->_onClause;
    }

    /**
     * @param array|string $fields
     * @return Join
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @param string $alias
     * @return Join
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }


}
