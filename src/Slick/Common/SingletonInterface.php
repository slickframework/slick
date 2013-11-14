<?php

/**
 * SingletonInterface
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

/**
 * SingletonInterface
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
interface SingletonInterface
{

     public static function getInstance($options = array());
}