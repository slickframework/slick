<?php

/**
 * Database connector
 * 
 * @package    Slick\Database
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Database;

use Slick\Common\Base;

/**
 * Abstract class defining a database connector type.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Connector extends Base implements Connector\ConnectorInterface
{
    /**
     * Returns a self instance of connetor.
     *
     * @return \Slick\Database\Connector
     */
    public function initialize()
    {
        return $this;
    }
}
