<?php

/**
 * Production error handler
 *
 * @package   Slick\Mvc\Whoops\Handler
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Whoops\Handler;

use Slick\Log\Log;
use Slick\Mvc\Application;
use Slick\Template\Template;
use Whoops\Handler\PlainTextHandler;
use Slick\Mvc\Exception\ActionNotFoundException;
use Slick\Mvc\Exception\ControllerNotFoundException;

/**
 * Production error handler
 *
 * @package   Slick\Mvc\Whoops\Handler
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Production extends PlainTextHandler
{

    /**
     * @var Application
     */
    private $app;

    /**
     * Sets application
     *
     * @param Application $app
     * @return self
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Handle the exception
     */
    public function handle()
    {
        $exception = $this->getException();

        Log::logger('ErrorHandler')->error($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => sprintf(
                "%s on line %d",
                $exception->getFile(),
                $exception->getLine()
            )
        ]);

        $template = new Template();

        $template = $template->initialize();
        $view = 'errors/500.html.twig';
        $code = 500;
        if (
            ($exception instanceof ControllerNotFoundException) ||
            ($exception instanceof ActionNotFoundException)
        ) {
            $view = 'errors/404.html.twig';
            $code = 404;
        }

        $data = ['exception' => $exception];
        $html = $template->parse($view)
            ->process($data);

        $this->app->response->setStatusCode($code)
            ->setContent($html);

        $this->app->response->send();
    }

}