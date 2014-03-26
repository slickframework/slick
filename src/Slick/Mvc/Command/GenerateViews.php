<?php

/**
 * Generate views command
 *
 * @package   Slick\Mvc\Command
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command;

use Slick\FileSystem\Folder;
use Slick\Mvc\Command\Utils\ControllerData;
use Slick\Mvc\Command\Utils\ViewBuilder;
use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Helper\DialogHelper,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate views command
 *
 * @package   Slick\Mvc\Command
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateViews extends Command
{

    /**
     * @var string controller file path
     */
    protected $_path;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName("generate:views")
            ->setDescription("Generate view files for a provided model name.")
            ->addArgument(
                'modelName',
                InputArgument::REQUIRED,
                'Full qualified model class name'
            )
            ->addOption(
                'view',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Tells witch view to generate.',
                ['index', 'show', 'add', 'edit']
            )
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Sets the application path where views are located',
                getcwd()
            )
            ->addOption(
                'out',
                'o',
                InputOption::VALUE_OPTIONAL,
                'The views folder where to save the view templates.',
                'Views'
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
        $output->writeln($this->getApplication()->getLongVersion());
        $output->writeln(
            "Generate views for model ".
            $input->getArgument('modelName')
        );
        $output->writeln("");

        $ctrlData = new ControllerData(
            [
                'controllerName' => $input->getArgument('modelName'),
                'namespace' => $input->getOption('out'),
                'modelName' => $input->getArgument('modelName')
            ]
        );

        $this->_path = $input->getOption('path');
        $this->_path .= '/'. $input->getOption('out');
        $this->_path .= '/'. strtolower($ctrlData->getControllerSimpleName());

        $viewBuilder = new ViewBuilder($ctrlData);

        foreach (array_keys($viewBuilder->templates) as $name) {
            if (in_array($name, $input->getOption('view'))) {
                $this->saveFile($name, $viewBuilder->getCode($name), $output);
            }
        }
    }

    /**
     * Saves current data into a template file
     *
     * @param string $name
     * @param string $data
     * @param OutputInterface $output
     */
    protected function saveFile($name, $data, OutputInterface $output)
    {
        $folder = new Folder(['name' => $this->_path]);
        $fileName = "{$name}.html.twig";

        /** @var DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');
        $name = $folder->details->getRealPath() . '/'. $fileName;

        $save = true;
        if ($folder->hasFile($fileName)) {
            $output->writeln("<comment>File '{$name}' already exists.</comment>");
            if (!$dialog->askConfirmation(
                $output,
                '<question>Do you want to override existing file?</question>',
                false
            )) {
                $save = false;
            }
        }

        if ($save) {
            $folder->addFile($fileName)
                ->write($data);
            $output->writeln("<info>'{$name}' template file generated successfully!</info>");

        } else {
            $output->writeln("<comment>'{$fileName}' template file was not created.</comment>");
        }
    }
} 