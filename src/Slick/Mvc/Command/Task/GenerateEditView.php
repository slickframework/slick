<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 01-12-2014
 * Time: 10:33
 */

namespace Slick\Mvc\Command\Task;


class GenerateEditView extends GenerateViewTask
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/edit-view.twig';

    /**
     * @var string
     */
    protected $_objectType = 'Edit View';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return lcfirst($this->_controllerData->getControllerSimpleName()) .
        '/edit.html.twig';
    }

}
