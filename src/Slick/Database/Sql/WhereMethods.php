<?php

/**
 * Sql where clause methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

/**
 * Sql where clause methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait WhereMethods
{

    /**
     * @var array List of where conditions
     */
    protected $_where = [];

    /**
     * @var array List of parameters
     */
    protected $_parameters = [];

    /**
     * @param string|array $condition
     * @param string $operation
     *
     * @return Select|Delete
     */
    protected function _setWhere($condition, $operation = 'AND')
    {

        if (is_string($condition)) {
            $this->_where[] = [
                'operation' => $operation,
                'condition' => $condition
            ];
            return $this;
        }

        if (is_array($condition) || ($condition instanceof \Traversable)) {
            $conditions = [];
            foreach ($condition as $predicate => $param) {
                if (is_array($param) || ($param instanceof \Traversable)) {
                    //param has multiple entries
                    foreach ($param as $key => $value) {
                        if (preg_match('/:[a-z_]*/i', $key)) {
                            $this->_parameters[$key] = $value;
                        } else {
                            $this->_parameters[] = $value;
                        }
                    }
                } else {
                    $this->_parameters[] = $param;
                }
                $conditions[] = $predicate;
            }
            $this->_where[] = [
                'operation' => $operation,
                'condition' => implode(' AND ', $conditions)
            ];
        }
        return $this;
    }

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete
     */
    public function where($condition)
    {
        return $this->_setWhere($condition);
    }

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete
     */
    public function andWhere($condition)
    {
        return $this->_setWhere($condition);
    }

    /**
     * Returns the parameters entered in conditions
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete
     */
    public function orWhere($condition)
    {
        return $this->_setWhere($condition, 'OR');
    }
}
