<?php

/**
 * Controller meta data
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command\Utils;

use Slick\Common\Base,
    Slick\Utility\Text;

/**
 * Controller meta data
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerData extends Base
{
    /**
     * @readwrite
     * @var string
     */
    protected $_controllerName;

    /**
     * @readwrite
     * @var string
     */
    protected $_namespace;

    /**
     * @readwrite
     * @var string
     */
    protected $_modelName;

    /**
     * Sets controller namespace
     *
     * @param string $namespace
     *
     * @return ControllerData
     */
    public function setNameSpace($namespace)
    {
        $this->_namespace = str_replace('/', '\\', $namespace);
        return $this;
    }

    /**
     * Sets controller name
     *
     * @param string $modelName
     *
     * @return ControllerData
     */
    public function setControllerName($modelName)
    {
        $name = end(explode('/', $modelName));
        $this->_controllerName = ucfirst(Text::plural($name));
        return $this;
    }

    /**
     * Sets model name
     *
     * @param string $modelName
     *
     * @return ControllerData
     */
    public function setModelName($modelName)
    {
        $this->_modelName = str_replace('/', '\\', $modelName);
        return $this;
    }

    /**
     * Return controller class name
     *
     * @return string
     */
    public function getControllerSimpleName()
    {
        return end(explode('\\', $this->controllerName));
    }

    /**
     * Returns model class name
     *
     * @return string
     */
    public function getModelSimpleName()
    {
        return end(explode('\\', $this->modelName));
    }

    /**
     * Returns lowercase model name in plural form
     *
     * @return string
     */
    public function getModelPlural()
    {
        return strtolower(Text::plural($this->getModelSimpleName()));
    }

    /**
     * Return lowercase model name in singular form
     *
     * @return string
     */
    public function getModelSingular()
    {
        return strtolower($this->getModelSimpleName());
    }

    /**
     * Returns the form name for this controller
     *
     * @return string
     */
    public function getFormName()
    {
        return $this->getModelSimpleName() .'Form';
    }
} 