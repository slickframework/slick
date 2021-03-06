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

use Slick\Common\Base;
use Slick\Mvc\Model\Descriptor;
use Slick\Mvc\Scaffold\Form;
use Slick\Orm\Entity\Manager;
use Slick\Utility\Text;

/**
 * Controller meta data
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $modelName
 * @property string $nameSpace
 * @property string $controllerName
 *
 * @method string getModelName()
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
     * @readwrite
     * @var Descriptor
     */
    protected $_descriptor;

    /**
     * @readwrite
     * @var Form
     */
    protected $_form;

    /**
     * @readwrite
     * @var string
     */
    protected $_modelPlural;

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
        $this->_controllerName = ucfirst(Text::plural(lcfirst($name)));
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
        if (is_null($this->_modelPlural)) {
            $name = $this->getModelSimpleName();
            $names = Text::camelCaseToSeparator($name, '#');
            $names = explode('#', $names);
            $last = ucfirst(Text::plural(strtolower(array_pop($names))));
            $names[] = $last;
            $this->_modelPlural = lcfirst(implode('', $names));
        }
        return $this->_modelPlural;
    }

    /**
     * Return lowercase model name in singular form
     *
     * @return string
     */
    public function getModelSingular()
    {
        return lcfirst($this->getModelSimpleName());
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

    /**
     * Returns the model descriptor object
     *
     * @return Descriptor
     */
    public function getDescriptor()
    {
        if (is_null($this->_descriptor)) {
            $this->_descriptor = new Descriptor(
                [
                    'descriptor' => Manager::getInstance()
                        ->get($this->getModelName())
                ]
            );
        }
        return $this->_descriptor;
    }

    /**
     * Returns the default form for current controller
     *
     * @return int|Form|string
     */
    public function getForm()
    {
        if (is_null($this->_form)) {
            $this->_form = new Form("scaffold", $this->getDescriptor());
        }
        return $this->_form;
    }

    /**
     * Returns the model human name
     *
     * @return string
     */
    public function getModelHumanName()
    {
        $name = $this->getModelSimpleName();
        return ucfirst(strtolower(Text::camelCaseToSeparator($name, ' ')));
    }

    public function getModelHumanNamePlural()
    {
        $name = $this->getModelPlural();
        return ucfirst(strtolower(Text::camelCaseToSeparator($name, ' ')));
    }
}
