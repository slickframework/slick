<?php

/**
 * Session
 *
 * @package   Slick\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Session;

use Slick\Common\Base,
    Slick\Session\Driver;

/**
 * Session factory class
 *
 * @package   Slick\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com> 
 */
class Session extends Base
{
    
    /**
     * @readwrite
     * @var string Session type or driver class name.
     */
    protected $_class = 'server';

    /**
     * @readwrite
     * @var array A list of options for Session Driver.
     */
    protected $_options;

    /**
     * Returns a new session driver based on the class property.
     *
     * @return /Slick/Session/Driver A new Session driver based on class.
     */
    public function initialize()
    {
        $driver = $this->getClass();

        if (empty($driver)) {
            throw new Exception\InvalidArgumentException(
                "The session driver is invalid."
            );
        }

        // Load user defined driver
        if (class_exists($driver)) {
            $driverObj = new $driver($this->getOptions());
            if (is_a($driverObj, '\Slick\Session\Driver\DriverInterface')) {
                return $driverObj;
            } else {
                throw new Exception\InvalidArgumentException(
                    "The session type '{$driver}' doesn't inherited from "
                    ."Slick\Session\Driver\DriverInterface."
                );
            }
        }

        // Load module predifined drivers
        switch ($driver) {
            case 'server':
                $driverObj = new Driver\Server($this->getOptions());
                break;

            default:
                throw new Exception\InvalidArgumentException(
                    "The session type '{$driver}' isn't implemented."
                );
        }
       
        return $driverObj;
    }
}