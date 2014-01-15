<?php

/**
 * Cache
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache;

use Slick\Common\Base,
    Slick\Cache\Driver;

/**
 * Cache
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Cache extends Base
{
    
    /**
     * @readwrite
     * @var string Cache driver or driver class name
     */
    protected $_class = 'file';

    /**
     * @readwrite
     * @var array Driver configuration options
     */
    protected $_options = array();

    /**
     * Driver initialization
     * 
     * @return Driver A cache driver
     * 
     * @throws Exception\DriverNotFounf If the driver type or class name
     *  ins not a valid cache driver.
     */
    public function initialize()
    {
        $driver = null;

        if (class_exists($this->_class)) {
            $class = $this->_class;
            return new $class($this->_options);
        }

        switch (strtolower($this->_class)) {

            case 'file':            
                $driver = new Driver\File($this->_options);
                break;

            default:

        }
        return $driver;
    }
}