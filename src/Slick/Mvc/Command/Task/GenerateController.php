<?php

/**
 * Generate controller content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Command\Task;

/**
 * Generate controller content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateController extends GenerateScaffoldController
{

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/controller.twig';
} 