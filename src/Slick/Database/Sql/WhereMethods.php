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
    protected $where = [];

    /**
     * @var array List of parameters
     */
    protected $parameters = [];

    /**
     * @param string|array $condition
     * @param string $operation
     *
     * @return Select|Delete
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *  it has 11 for a test limit of 10
     */
    protected function _setWhere($condition, $operation = 'AND')
    {

        if (is_string($condition)) {
            $this->where[] = [
                'condition' => $condition,
                'operation' => $operation
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
                            $this->parameters[$key] = $value;
                        } else {
                            $this->parameters[] = $value;
                        }
                    }
                } elseif (!is_numeric($predicate)) {
                    $this->parameters[] = $param;
                } else {
                    $predicate = $param;
                }
                $conditions[] = $predicate;
            }
            $this->where[] = [
                'condition' => count($conditions) > 1 ?
                        '('. implode(' AND ', $conditions) .')' :
                        $conditions[0],
                'operation' => $operation
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
        return $this->parameters;
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

    /**
     * Returns the where statement for sql
     *
     * @return bool|string
     */
    public function getWhereStatement()
    {
        if (empty($this->where)) {
            return false;
        }
        $this->where[0]['operation'] = null;
        $str = '';
        foreach ($this->where as $clause) {
            $str .= trim("{$clause['operation']} {$clause['condition']}");
            $str .= " ";
        }

        return trim($str);
    }
}
