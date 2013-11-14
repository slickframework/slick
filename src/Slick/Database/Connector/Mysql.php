<?php

/**
 * Mysql
 * 
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

use Slick\Common\SingletonInterface;

/**
 * Mysql database connector
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends AbstractConnector implements SingletonInterface
{

    private function __construct($options = array())
    {
        parent::__construct($options);
    }

    public static function getInstance($options = array())
    {
        static $instance;
        if (
            !is_a(
                $instance,
                'Slick\Database\Connector\ConnectorInterface'
            )
        ) {
            $instance = new Mysql($options);
        }
        return $instance;
    }

    public function getDsn()
    {
        $dsn = "mysql:host=%s;dbname=%s";
        return sprintf(
            $dsn,
            $this->options->getProperty('hostname'),
            $this->options->getProperty('database')
        );
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {
    
    }
}