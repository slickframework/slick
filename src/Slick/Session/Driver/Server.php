<?php

/**
 * Server driver
 *
 * @package   Slick\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Session\Driver;

/**
 * Session Server driver (Default php session handling)
 *
 * @package   Slick\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Server extends Driver
{

    /**
     * @readwrite
     * @var string Session cookie name
     */
    protected $_name = 'SLICKSID';

    /**
     * @readwrite
     * @var string Session cookie domain
     */
    protected $_domain = null;

    /**
     * @readwrite
     * @var integer Session cookie lifetime
     */
    protected $_lifetime = 0;

    /**
     * Overrides base constructor to set parameters and initialize session.
     *
     * @see \Slick\Common\Base
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        session_set_cookie_params($this->lifetime, '/', $this->domain);
        session_name($this->name);
        @session_start();

    }

    /**
     * Save session values before destruction.
     * 
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        session_commit();
    }
}