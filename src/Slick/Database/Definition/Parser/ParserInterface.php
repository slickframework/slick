<?php

/**
 * ParserInterface
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition\Parser;

/**
 * ParserInterface
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ParserInterface
{

    /**
     * Returns the columns on this data definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList A Column list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::Column
     */
    public function getColumns();
}