<?php

/**
 * Conditions
 * 
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

use Slick\Common\Base;

/**
 * Conditions
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Conditions extends Base
{
    /**
     * @readwrite
     * @var array A list of predicates (conditions)
     */
    protected $_predicates = array();

    /**
     * @readwrite
     * @var array A list of operations that will tie the predicates
     */
    protected $_operations = array();

    public function addPredicate($predicate)
    {
        $this->_predicates[] = $predicate;
        return $this;
    }

    public function addOperation($operation)
    {
        if (sizeof($this->_predicates) > 1) {
            $this->_operations[] = $operation;
        }
        return $this;
    }

    public function toString()
    {
        $str = '';

        $numPredicates = sizeof($this->_predicates);
        for ($i = 0; $i < $numPredicates; $i++) {
            
            $str .= "{$this->_predicates[$i]} ";

            // middle element, add operation
            if (isset($this->_predicates[$i+1])) {
                $str .= "{$this->_operations[$i]} ";
            } 
        }

        return trim($str);
    }
}