<?php

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Orm\Entity\Manager;
use Slick\Template\Template;
use Slick\Utility\Text;

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Controller $controller
 * @property string $scaffoldControllerName
 * @property string $modelName
 *
 * @method Controller getController() Returns the controller being scaffold
 */
class Scaffold extends Controller
{

    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * @readwrite
     * @var string
     */
    protected $_modelName;

    /**
     * @readwrite
     * @var string
     */
    protected $_scaffoldControllerName;

    /**
     * Set common variables for views
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $nameParts = explode("\\", get_class($this->_controller));
        $this->_scaffoldControllerName = end($nameParts);
        $this->set('modelPlural', strtolower(end($nameParts)));
        $this->set(
            'modelSingular',
            Text::singular(strtolower(end($nameParts)))
        );
        Template::appendPath(__DIR__ . '/Views');
    }

    /**
     * Creates a new scaffold controller
     *
     * @param Controller $instance
     * @param array $options
     *
     * @return self
     */
    public static function getScaffoldController(
        Controller $instance, $options = [])
    {
        $options = array_merge(['controller' => $instance], $options);
        return new static($options);
    }

    /**
     * Returns the model class name
     *
     * @return string
     */
    public function getModelName()
    {
        if (is_null($this->_modelName)) {
            $this->setModelName('Models\\' .
                Text::singular($this->_scaffoldControllerName));
        }
        return $this->_modelName;
    }

    /**
     * Sets model name
     *
     * @param string $name
     *
     * @return self
     */
    public function setModelName($name)
    {
        $this->_modelName = $name;
        $nameParts = explode("\\", $name);
        $this->set('modelPlural', strtolower(Text::plural(end($nameParts))));
        $this->set(
            'modelSingular',
            strtolower(Text::singular(end($nameParts)))
        );
        return $this;
    }

    public function index()
    {
        $this->view = 'scaffold/index';
        $this->set('descriptor', Manager::getInstance()->get($this->_modelName));
        
    }
}
