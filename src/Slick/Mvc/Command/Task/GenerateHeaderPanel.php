<?php

/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 28-11-2014
 * Time: 11:15
 */

namespace Slick\Mvc\Command\Task;


class GenerateHeaderPanel extends GenerateViewTask
{
    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/panel-heading-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Panel heading View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
        '/panel-heading.twig';
    }
}
