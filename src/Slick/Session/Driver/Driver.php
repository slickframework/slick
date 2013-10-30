<?php

/**
 * Driver
 *
 * @package   Slick\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Session\Driver;

use Slick\Common\Base;

/**
 * Session driver, base class for all session drivers
 *
 * @package   Slick\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @see  \Slick\Session\Driver\DriverInterface
 */
abstract class Driver extends Base implements DriverInterface
{

    /**
     * @readwrite
     * @var string Session prefix on key names
     */
    protected $_prefix = "slick_";

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $prefix = $this->prefix;

        if (isset($_SESSION[$prefix.$key])) {
            return $_SESSION[$prefix.$key];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $prefix = $this->prefix;
        $_SESSION[$prefix.$key] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function erase($key)
    {
        $prefix = $this->prefix;
        unset($_SESSION[$prefix.$key]);
        return $this;
    }
}