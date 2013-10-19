<?php

/**
 * Car class example
 * 
 * @package    Test\Common\Examples
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Common\Examples;

/**
 * Car is an example class used to test the \Slick\Common\Inspector class
 *
 * @package    Test\Common\Examples
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @test
 */
class Car
{
    
    /**
     * @readwrite
     * @var string The car brand 
     */
    protected $_brand;
    
    protected $_model;
    
    /**
     * @return boolean The car state
     * @throws \Exception
     */
    public function start()
    {
        
    }
    
    public function stop()
    {
        
    }
}

class Motor
{
    
}