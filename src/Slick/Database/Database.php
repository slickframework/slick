<?php

/**
 * Database
 * 
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database;

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Database is a factory for a database connector object.
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Database extends Base
{
    /**
     * @readwrite
     * @var string Database type name
     */
    protected $_type;

    /**
     * @readwrite
     * @var array Options to pass to connector initialization.
     */
    protected $_options;
    
    /**
     * Initializes an database connector.
     *             
     * @return \Slick\Database\Connector The database connector instance.
     */
    public function initialize()
    {
        if (!$this->_type) {
            throw new Exception\InvalidArgumentException(
                "Trying to initialize a database conncetor with an undefined"
                . " connector type."
            );
        }
        switch ($this->_type) {
            case 'mysql':
                $connector = new Connector\Mysql($this->options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "Trying to initialize a database conncetor with an unknown"
                    . " connector type."
                );
        }
        return $connector;
    }
}
