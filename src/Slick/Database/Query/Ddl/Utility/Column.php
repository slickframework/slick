<?php

/**
 * Column
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

use Slick\Common\Base;

/**
 * Column - A database column definition
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Column extends Base implements TableElementInterface
{

    protected $_name;

    protected $_type;
}