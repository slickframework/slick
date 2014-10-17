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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    /**
     * Runs the task
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        parent::run($input, $output);
        $formTask = new GenerateForm(
            [
                'command' => $this->_command,
                'controllerData' => $this->_controllerData,
                'path' => $this->getPath()
            ]
        );
        return $formTask->run($input, $output);
    }
} 