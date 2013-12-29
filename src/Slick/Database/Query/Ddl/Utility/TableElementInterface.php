<?php

/**
 * TableElementInterface
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

/**
 * TableElementInterface
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface TableElementInterface
{
    
    /**
     * Compares current object with provided one for equality
     * 
     * @param mixed|object $object The object to compare with
     * 
     * @return boolean True if the provided object is equal to this object
     */
    public function equals($object);
}