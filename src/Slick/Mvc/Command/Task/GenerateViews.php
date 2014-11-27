<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 26-11-2014
 * Time: 18:27
 */

namespace Slick\Mvc\Command\Task;


use Slick\Common\Base;
use Slick\Mvc\Command\Utils\ControllerData;
use Slick\Template\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateViews extends Base implements TaskInterface
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
    protected $_path;

    /**
     * @readwrite
     * @var Command
     */
    protected $_command;

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
        Template::addPath(dirname(dirname(__DIR__)) .'/Views');
        $task = new GenerateQuickLinks([
            'command' => $this->_command,
            'controllerData' => $this->_controllerData,
            'path' => $this->_path
        ]);
        $task->run($input, $output);
        $this->generateIndex($input, $output);
    }

    public function generateAll(InputInterface $input, OutputInterface $output) {
        $this->generateIndex($input, $output);
        return $this;
    }

    public function generateIndex(InputInterface $input, OutputInterface $output)
    {
        $task = new GenerateIndexView([
            'command' => $this->_command,
            'controllerData' => $this->_controllerData,
            'path' => $this->_path
        ]);
        $task->run($input, $output);
        return $this;
    }
}