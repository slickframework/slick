<?php

/**
 * Configuration
 *
 * @package   Slick\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration;

use Slick\Common\Base;

/**
 * Configuration
 *
 * @package   Slick\Configuration
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Configuration extends Base
{
    
    /**
     * @readwrite
     * @var string Configuration type or driver class name.
     */
    protected $_class = 'ini';

    /**
     * @readwrite
     * @var array A list of options for Configuration Driver.
     */
    protected $_options;

    /**
     * [initialize description]
     * @return [type] [description]
     */
    public function initialize()
    {
        $class = $this->getClass();
        $driver = null;

        if (empty($class)) {
            throw new Exception\InvalidArgumentException(
                "The configuration driver is invalid."
            );
        }

        // Load user defined driver
        if (class_exists($class)) {
            $driver = new $class($this->_options);
            if (is_a($driver, '\Slick\Configuration\Driver\DriverInterface')) {
                return $driver;
            } else {
                throw new Exception\InvalidArgumentException(
                    "The configuration type '{$class}' doesn't inherited from "
                    ."Slick\Configuration\Driver\DriverInterface."
                );
            }
        }

        switch ($class) {
            case 'ini':
                $driver = new Driver\Ini($this->_options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "The configuration driver is unknown."
                );
        }

        return $driver;
    }
}