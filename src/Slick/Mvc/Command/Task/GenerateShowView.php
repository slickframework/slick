<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 01-12-2014
 * Time: 11:11
 */

namespace Slick\Mvc\Command\Task;


class GenerateShowView extends GenerateViewTask
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/show-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Show View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
        '/show.html.twig';
    }
}
