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

use Slick\Template\Template,
    Slick\Mvc\Command\Utils\ControllerData;
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

    protected $_path;
    protected $_controllerFile;

    /**
     * Meta data for controller creation
     * @var ControllerData
     */
    protected $_controllerData;

    /**
     * @var
     */
    protected $_template;

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
                null,
                InputOption::VALUE_OPTIONAL,
                'Sets the application path where controllers are located',
                getcwd()
            )
            ->addOption(
                'out',
                null,
                InputOption::VALUE_OPTIONAL,
                'The controllers folder where to save the controller. Defaults to "Controllers".',
                'Controllers'
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

        $output->writeln($this->getCode());
        return null;
    }



    protected function getTemplate()
    {
        if (is_null($this->_template)) {
            Template::addPath(dirname(__DIR__) .'/'. 'Views');
            $template = new Template(['engine' => 'twig']);
            $this->_template = $template->initialize();
        }
        return $this->_template;
    }

    protected function getCode()
    {
        return $this->getTemplate()
            ->parse('template/controller.php.twig')
            ->process(['command' => $this->_controllerData]);
    }

    /**
     * @return mixed
     */
    public function getControllerFile()
    {
        return $this->_controllerFile;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }


} 