<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Sql where clause methods
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait WhereMethods
{

    /**
     * @var array List of where conditions
     */
    protected $where = [];

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete|Update
     */
    public function where($condition)
    {
        return $this->setWhere($condition);
    }

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete|Update
     */
    public function andWhere($condition)
    {
        return $this->where($condition);
    }

    /**
     * Sets SQL where condition for this statement
     *
     * @param string $condition
     * @return Select|Delete|Update
     */
    public function orWhere($condition)
    {
        return $this->setWhere($condition, 'OR');
    }

    /**
     * Returns the parameters to be bound to query string by adapter
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Returns the where statement for sql
     *
     * @return null|string
     */
    public function getWhereStatement()
    {
        if (empty($this->where)) {
            return null;
        }
        $this->where[0]['operation'] = null;
        $str = '';
        foreach ($this->where as $clause) {
            $str .= trim("{$clause['operation']} {$clause['condition']}");
            $str .= " ";
        }
        return trim($str);
    }

    /**
     * @param string|array $condition
     * @param string $operation
     *
     * @return Select|Delete|Update
     */
    protected function setWhere($condition, $operation = 'AND')
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
                $conditions[] = $this->parserParameters($predicate, $param);
            }
            $this->where[] = [
                'condition' => count($conditions) > 1 ?
                    '('.implode(' AND ', $conditions).')' : $conditions[0],
                'operation' => $operation
            ];
        }
        return $this;
    }

    /**
     * Parses the parameters on where clause array
     *
     * @param string $predicate
     * @param mixed $param
     *
     * @return string
     */
    protected function parserParameters($predicate, $param)
    {
        $result = $param;
        if (is_array($param) || ($param instanceof \Traversable)) {
            //param has multiple entries
            foreach ($param as $key => $value) {
                if (preg_match('/:[a-z_]*/i', $key)) {
                    $this->parameters[$key] = $value;
                } else {
                    $this->parameters[] = $value;
                }
            }
            $result = $predicate;
        } elseif (!is_numeric($predicate)) {
            $this->parameters[] = $param;
            $result = $predicate;
        }

        return $result;
    }
}