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
    Slick\Utility\ProperyList,
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
     * @var string Contains the name of the connector to use
     */
    protected $_type = 'mysql';

    /**
     * @readwrite
     * @var \Slick\Utility\PropertyList A list of connector options
     */
    protected $_options = null;

    /**
     * Sets the options for this driver.
     *
     * This methos ensures that options is a property list.
     * 
     * @param \Slick\Utility\PropertyList $options Connector options
     *
     * @return \Slick\Database\Database A self instance for method call chains
     */
    public function setOptions(\Slick\Utility\PropertyList $options)
    {
        $this->_options = $options;
        return $this;
    }

    /**
     * Initializes an database connector.
     * 
     * @return \Slick\Database\ConnectorInterface The database connector
     *  instance.
     */
    public function initialize()
    {
        if (!$this->_type) {
            throw new Exception\InvalidArgumentException(
                "Trying to initialize a database conncetor with an undefined ".
                "connector type."
            );
        }

        switch ($this->_type) {
            case 'mysql':
                $connector = Connector\Mysql::getInstance($this->_options);
                break;

            case 'sqlite':
                $connector = Connector\SQLite::getInstance($this->_options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "Trying to initialize a database conncetor with an ".
                    "unknown connector type."
                );
        }

        return $connector;
    }
}