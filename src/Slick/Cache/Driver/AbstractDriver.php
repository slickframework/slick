<?php

/**
 * AbstractDriver
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Driver;

use Slick\Common\Base,
    Slick\Cache\DriverInterface;

/**
 * Wrapper for common properties and methods among cache drivers
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string  $prefix   Prefix for cache key
 * @property integer $duration Number of seconds for cache expiry
 */
abstract class AbstractDriver extends Base implements DriverInterface
{

    /**
     * @readwrite
     * @var string The prefix for cache key
     */
    protected $_prefix = '';

    /**
     * @readwrite
     * @var integer The number of seconds for cache expiry
     */
    protected $_duration = 120;
    
    /**
     * Handle the initialization of an already initialized driver
     * 
     * @return AbstractDriver
     */
    public function initialize()
    {
        return $this;
    }
}