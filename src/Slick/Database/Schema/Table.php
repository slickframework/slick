<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/25/14
 * Time: 5:25 PM
 */

namespace Slick\Database\Schema;


use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

class Table
{

    /**
     * @var ColumnInterface[]
     */
    protected $_columns = [];

    /**
     * @var ConstraintInterface[]
     */
    protected $_constraints = [];
} 