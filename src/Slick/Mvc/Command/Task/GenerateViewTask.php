<?php

/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 27-11-2014
 * Time: 10:32
 */

namespace Slick\Mvc\Command\Task;

use Slick\Common\Base;
use Slick\FileSystem\Folder;
use Slick\Template\Template;
use Slick\Mvc\Command\Utils\ControllerData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class GenerateViewTask
 * @package Slick\Mvc\Command\Task
 *
 * @method string getPath() Returns the file path
 */
abstract class GenerateViewTask extends Base implements TaskInterface
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
    protected $_template = 'templates/index-view.twig';

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
     * @var string
     */
    protected $_objectType = 'View';

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

        $fileName = $this->_getFileName();
        $folder = new Folder(['name' => $this->getPath()]);
        if (
            !$folder->hasFolder(
                lcfirst($this->_controllerData->getControllerSimpleName())
            )
        ) {
            $folder->addFolder(
                lcfirst($this->_controllerData->getControllerSimpleName())
            );
        }

        /** @var QuestionHelper $dialog */
        $dialog = $this->_command->getHelperSet()->get('question');
        $name = $folder->details->getRealPath() . '/'. $fileName;
        $save = true;

        if ($folder->hasFile($fileName)) {
            $output->writeln("<comment>File '{$name}' already exists.</comment>");
            $question = new Question(
                '<question>Do you want to override existing file?</question>',
                false
            );
            if (!$dialog->ask($input, $output, $question)) {
                $save = false;
            }
        }

        if ($save) {
            $folder->addFile($fileName)
                ->write($content);
            $output->writeln("<info>{$this->_objectType} file generated successfully!</info>");
        } else {
            $output->writeln("<comment>{$this->_objectType} file was not created.</comment>");
        }
        $output->writeln('');
    }

    /**
     * Returns the file name to output
     *
     * @return string
     */
    abstract protected function _getFileName();
}
