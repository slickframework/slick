<?php

/**
 * Generate controller command
 *
 * @package   Slick\Mvc\Command
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command;

use Slick\Mvc\Command\Task\GenerateScaffoldController;
use Slick\Mvc\Command\Utils\ControllerData;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate controller command
 *
 * @package   Slick\Mvc\Command
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateController extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName("generate:controller")
            ->setDescription("Generate a controller file for the provided model name.")
            ->addArgument(
                'modelName',
                InputArgument::REQUIRED,
                'Full qualified model class name'
            )
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Sets the application path where controllers are located',
                getcwd()
            )
            ->addOption(
                'out',
                'o',
                InputOption::VALUE_OPTIONAL,
                'The controllers folder where to save the controller.',
                'Controllers'
            )
            ->addOption(
                'scaffold',
                'S',
                InputOption::VALUE_NONE,
                'If set the controller will have only the scaffold property set.'
            );
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|integer null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     * @see    setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var Application $application */
        $application = $this->getApplication();
        $output->writeln($application->getLongVersion());
        $output->writeln(
            "Generate controller for model ". $input->getArgument('modelName')
        );
        $output->writeln("");

        $controllerData = new ControllerData(
            [
                'controllerName' => $input->getArgument('modelName'),
                'namespace' => $input->getOption('out'),
                'modelName' => $input->getArgument('modelName')
            ]
        );

        $path = $input->getOption('path');
        $path .= '/'. $input->getOption('out');

        if ($input->getOption('scaffold')) {
            $task = new GenerateScaffoldController(
                [
                    'command' => $this,
                    'controllerData' => $controllerData,
                    'path' => $path
                ]
            );
            $task->run($input, $output);
        }
    }
}