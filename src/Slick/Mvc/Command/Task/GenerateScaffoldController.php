<?php

/**
 * Generate scaffold controller content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Command\Task;

use Slick\Common\Base;
use Slick\FileSystem\Folder;
use Slick\Mvc\Command\Utils\ControllerData;
use Slick\Template\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate scaffold controller content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class GenerateScaffoldController extends Base implements TaskInterface
{

    /**
     * @readwrite
     * @var ControllerData
     */
    protected $_controllerData;

    /**
     * @readwrite
     * @var string
     */
    protected $_template = 'templates/scaffold-controller.twig';

    /**
     * @readwrite
     * @var string
     */
    protected $_path;

    /**
     * @readwrite
     * @var Command
     */
    protected $_command;

    /**
     * Set template path
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        Template::addPath(dirname(dirname(__DIR__)) .'/Views');
    }

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
        $template = new Template();
        $template = $template->initialize();

        $data = ['command' => $this->_controllerData];
        $content = $template->parse($this->_template)->process($data);

        $fileName = $this->_controllerData->getControllerSimpleName() .'.php';
        $folder = new Folder(['name' => $this->_path]);

        /** @var DialogHelper $dialog */
        $dialog = $this->_command->getHelperSet()->get('dialog');
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
                ->write($content);
            $output->writeln("<info>Controller file generated successfully!</info>");
        } else {
            $output->writeln("<comment>Controller file was not created.</comment>");
        }
    }
}