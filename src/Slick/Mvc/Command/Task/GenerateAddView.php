<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 28-11-2014
 * Time: 18:29
 */

namespace Slick\Mvc\Command\Task;


class GenerateAddView extends GenerateViewTask
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/add-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Add View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
        '/add.html.twig';
    }

} 