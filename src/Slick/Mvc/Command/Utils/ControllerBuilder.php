<?php

/**
 * Controller builder
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command\Utils;

use Slick\Common\Base,
    Slick\Template\Template,
    Slick\Template\Engine\Twig;

/**
 * Controller builder
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerBuilder extends Base
{

    /**
     * @readwrite
     * @var ControllerData
     */
    protected $_controllerData;

    /**
     * @readwrite
     * @var Twig Template engine
     */
    protected $_template;

    /**
     * Template used to render the code
     *
     * @return Twig|\Slick\Template\EngineInterface
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            Template::addPath(dirname(dirname(__DIR__)) .'/'. 'Views');
            $template = new Template(['engine' => 'twig']);
            $this->_template = $template->initialize();
        }
        return $this->_template;
    }

    /**
     * Returns the controller code for current controller data
     *
     * @return string The controller code
     */
    public function getCode()
    {
        return $this->getTemplate()
            ->parse('template/controller.php.twig')
            ->process(['command' => $this->_controllerData]);
    }
} 