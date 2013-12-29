<?php

/**
 * Definition Parser
 *
 * @package   Slick\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition;

use Slick\Database\RecordList,
    Slick\Database\Definition\Parser;

/**
 * Definition Parser
 *
 * @package   Slick\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Parser
{
    
    /**
     * Factory method to create a parser for the given dialect
     * 
     * @param  string $dialect
     * @param  \Slick\Database\RecordList $data 
     * 
     * @return Slick\Database\Definition\Parser\ParserInterface
     */
    public static function getParser($dialect, RecordList $data)
    {
        $parser = null; 
        switch (strtolower($dialect)) {
            case 'mysql':
                $parser = new Parser\Mysql(array('data' => $data));
                break;
            
            default:
                $parser = new Parser\SQLite(array('data' => $data));
                break;
        }
        return $parser;
    }
}