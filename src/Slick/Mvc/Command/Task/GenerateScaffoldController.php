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
use Slick\Configuration\Configuration;
use Slick\FileSystem\Folder;
use Slick\Mvc\Command\Utils\ControllerData;
use Slick\Template\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Generate scaffold controller content
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method string getPath() Returns the file path
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
     * @var string
     */
    protected $_objectType = 'Controller';

    /**
     * Set template path
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        Template::addPath(dirname(dirname(__DIR__)) .'/Views');
        Configuration::addPath(dirname($this->_path) .'/Configuration');
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

        $fileName = $this->_getFileName();
        $folder = new Folder(['name' => $this->getPath()]);

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
    protected function _getFileName()
    {
        return $this->_controllerData->getControllerSimpleName() .'.php';
    }
}