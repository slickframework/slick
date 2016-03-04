<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 27-11-2014
 * Time: 10:56
 */

namespace Slick\Mvc\Command\Task;


class GenerateIndexView extends GenerateViewTask
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/index-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Index View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
            '/index.html.twig';
    }
}