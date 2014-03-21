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

use Slick\Mvc\Command\Utils\ControllerData,
    Slick\Mvc\Command\Utils\ControllerBuilder;
use Slick\Mvc\Command\Utils\FormBuilder;
use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate controller command
 *
 * @package   Slick\Mvc\Command
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateController extends Command
{

    /**
     * @var string controller file path
     */
    protected $_path;

    /**
     * @var string controller file nam  e
     */
    protected $_controllerFile;

    /**
     * Meta data for controller creation
     * @var ControllerData
     */
    protected $_controllerData;

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
        $this->_controllerData = new ControllerData(
            [
                'controllerName' => $input->getArgument('modelName'),
                'namespace' => $input->getOption('out'),
                'modelName' => $input->getArgument('modelName')
            ]
        );

        $controllerBuilder = new ControllerBuilder(
            ['controllerData' => $this->_controllerData]
        );

        $formBuilder = new FormBuilder($this->_controllerData);

        $output->writeln($formBuilder->getCode());
        return null;
    }

} 