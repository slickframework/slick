<?php

/**
 * AbstractParser
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition\Parser;

use Slick\Common\Base;

/**
 * AbstractParser
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractParser extends Base implements ParserInterface
{

    /**
     * @readwrite
     * @var \Slick\Database\RecordList
     */
    protected $_data = null;

}