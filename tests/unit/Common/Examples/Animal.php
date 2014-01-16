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

use Slick\Common\Base;

/**
 * Animal is an example class used to test the \Slick\Common\Base class
 *
 * @package    Test\Common\Examples
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Animal extends Base
{

    /**
     * @readwrite
     * @var string Animal name
     */
    protected $_name;

    /**
     * @readwrite
     * @var boolean Is a pet animal
     */
    protected $_pet = false;

    /**
     * @read
     * @var boolean Animal state
     */
    protected $_dead = false;

    /**
     * @write  
     * @var boolean Animal medical state
     */
    protected $_sick = false;

}

/**
 * BadAnimal is an example class used to test the \Slick\Common\Base class
 *
 * @package    Test\Common\Examples
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class BadAnimal extends Base
{

    /**
     * @readwrite
     * @var string Animal name
     */
    protected $_name;

    /**
     * @readwrite
     * @var boolean Is a pet animal
     */
    protected $_pet = false;
    
    /**
     * Invalid contructor override.
     */
    public function __construct()
    {
        
    }

}
