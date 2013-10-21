<?php

/**
 * Query
 * 
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
namespace Slick\Database;

use Slick\Common\Base;

/**
 * Query class is what writes the vendor-specific database code.
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Query extends Base implements Query\QueryInterface
{
    public function all()
    {
        
    }

    public function count()
    {
        
    }

    public function delete()
    {
        
    }

    public function first()
    {
        
    }

    public function from($from, $fields = array())
    {
        
    }

    public function join($join, $on, $fields = array(), $type = 'LEFT')
    {
        
    }

    public function limit($limit, $page = 1)
    {
        
    }

    public function order($order, $direction = 'ASC')
    {
        
    }

    public function save($data)
    {
        
    }

    public function where()
    {
        
    }

}
