<?php

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;
use Slick\Template\Template;
use Slick\Utility\Text;

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Scaffold extends Controller
{
    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * @read
     * @var array supported scaffold actions
     */
    protected $_scaffoldActions = [
        'index', 'show', 'add', 'update', 'delete'
    ];

    /**
     * @readwrite
     * @var string The model name for this controller
     */
    protected $_modelName;

    /**
     * Overrides default constructor to set the controller and view names
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $nameParts = explode("\\", get_class($this->_controller));
        $this->_modelName = 'Models\\' . Text::singular(end($nameParts));
        $this->set('modelPlural', end($nameParts));
        $this->_controllerName = 'scaffold';
        Template::appendPath(__DIR__ . '/Scaffold/Views');
    }

    /**
     * Creates a new scaffold controller
     *
     * @param Controller $instance
     * @param array $options
     *
     * @return Scaffold
     */
    public static function getController(
        Controller $instance, array $options = [])
    {
        $options = array_merge(['controller' => $instance], $options);
        return new static($options);
    }

    public function index()
    {
        $records = call_user_func_array([$this->_modelName, 'all'], array());
        var_dump($records);
        $this->set(compact('records'));
    }

} 