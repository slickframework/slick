<?php

/**
 * Form builder
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command\Utils;

use Slick\Common\Base;

/**
 * Class FormBuilder
 * @package Slick\Mvc\Command\Utils
 */
class FormBuilder extends Base
{

    /**
     * @readwrite
     * @var ControllerData
     */
    protected $_controllerData;

    /**
     * Overrides default constructor to ensure controller data is set
     *
     * @param ControllerData $data
     * @param array $options
     */
    public function __construct(ControllerData $data, $options = [])
    {
        parent::__construct($options);
        $this->_controllerData = $data;
    }

    /**
     * Returns the form class content for the current controller data
     *
     * @return string Form class code
     */
    public function getCode()
    {
        // TODO: create form code
    }
}