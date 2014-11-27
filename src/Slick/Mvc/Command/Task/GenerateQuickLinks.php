<?php

/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 27-11-2014
 * Time: 14:58
 */

namespace Slick\Mvc\Command\Task;


class GenerateQuickLinks extends GenerateViewTask
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/quick-links-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Quick-links View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
        '/quick-links.twig';
    }
}
