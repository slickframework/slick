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
 * AbstractDriver
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDriver extends Base implements DriverInterface
{

    /**
     * @readwrite
     * @var string The prefix for cacke key
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