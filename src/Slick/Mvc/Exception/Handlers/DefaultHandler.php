<?php

/**
 * Exception default handler
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception\Handlers;

use Exception;
use Slick\Common\Base,
    Slick\Mvc\Exception\HandlerInterface;
use Slick\Template\Engine\Twig;
use Slick\Template\EngineInterface;
use Slick\Template\Template;
use Zend\Http\PhpEnvironment\Response;

/**
 * Exception default handler
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property int $statusCode
 */
class DefaultHandler extends Base implements HandlerInterface
{

    /**
     * @readwrite
     * @var Exception
     */
    protected $_exception;

    /**
     * @read
     * @var Response
     */
    protected $_response;

    /**
     * @readwrite
     * @var int The HTTP status code response
     */
    protected $_statusCode = 500;

    /**
     * @readwrite
     * @var Twig
     */
    protected $_template;

    /**
     * Returns handled exception
     *
     * @return Exception
     */
    public function getException()
    {
        return $this->_exception;
    }

    /**
     * Set the exception that will be handled
     *
     * @param Exception $exp
     *
     * @return DefaultHandler
     */
    public function setException(Exception $exp)
    {
        $this->_exception = $exp;
        return $this;
    }

    /**
     * Returns the HTTP response for this exception
     *
     * @return Response
     */
    public function getResponse()
    {
        if (is_null($this->_response)) {
            $this->_response = $this->_process();
        }
        return $this->_response;
    }

    /**
     * Lazy loads the HTML rendering engine
     *
     * @return Twig|EngineInterface
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $template = new Template(['engine' => 'twig']);
            $this->_template = $template->initialize();
        }
        return $this->_template;
    }

    /**
     * Processes the exception in to a response
     *
     * @return Response
     */
    protected function _process()
    {
        $this->getTemplate()->parse("errors/development.html.twig");
        $response = new Response();
        $response->setContent(
            $this->getTemplate()->process(
                [
                    'exception' => $this->getException(),
                    'handler' => $this
                ]
            )
        );
        $response->setStatusCode($this->statusCode);
        return $response;
    }

    public function getExceptionName()
    {
        return get_class($this->_exception);
    }
}