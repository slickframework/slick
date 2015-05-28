<?php
 /**
 * Join
 *
 * @package Slick\Database\Sql\Select
 * @author    Filipe Silva <filipe.silva@sata.pt>
 * @copyright 2014-2015 Grupo SATA
 * @since     v0.0.0
 */

namespace Slick\Database\Sql\Select;


use Slick\Database\Sql\Dialect\FieldListAwareInterface;

class Join implements FieldListAwareInterface
{

    /**#@+
     * @var string SQL join types
     */
    const JOIN_INNER = 'INNER';
    const JOIN_LEFT  = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_FULL  = 'FULL OUTER';
    /**#@- */

    /**
     * @var string Table name
     */
    private $table;

    /**
     * @var array|string The fields to retrieve
     */
    private $fields;

    /**
     * @var string The SQL join type
     */
    private $type;

    /**
     * @var string The join condition
     */
    private $onClause;

    /**
     * @var string The alias for this table join
     */
    private $alias;

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
        $this->table = $table;
        $this->onClause = $onClause;
        $this->fields = $fields;
        $this->type = $type;
    }
    /**
     * Returns the join type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Gets the join condition
     *
     * @return string
     */
    public function getOnClause()
    {
        return $this->onClause;
    }
    /**
     * Get join table fields to select
     *
     * @return array|string
     */
    public function getFields()
    {
        return $this->fields;
    }
    /**
     * Returns the join table name
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }
    /**
     * Sets the join table alias
     *
     * @param string $alias
     * @return Join
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
    /**
     * Returns the join table alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
    /**
     * Sets join fields to include in select statement
     *
     * @param string|array $fields
     *
     * @return Join
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
}