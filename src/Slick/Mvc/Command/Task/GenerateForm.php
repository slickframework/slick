<?php

/**
 * Generate form content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Command\Task;

/**
 * Generate form content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateForm extends GenerateScaffoldController
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/form.twig';

    /**
     * Returns the form path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_path . '/Forms';
    }

    /**
     * @var string
     */
    protected $_objectType = 'Form';

    /**
     * Returns the file name to output
     *
     * @return string
     */
    protected function _getFileName()
    {
        return "{$this->_controllerData->getControllerSimpleName()}Form.php";
    }
} 