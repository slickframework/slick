<?php

/**
 * Exception handler interface
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception;

use Exception;
use Zend\Http\PhpEnvironment\Response;

/**
 * Interface HandlerInterface
 * @package Slick\Mvc\Exception
 */
interface HandlerInterface
{

    /**
     * Returns handled exception
     *
     * @return Exception
     */
    public function getException();

    /**
     * Set the exception that will be handled
     *
     * @param Exception $exp
     *
     * @return HandlerInterface
     */
    public function setException(Exception $exp);

    /**
     * Returns the HTTP response for this exception
     *
     * @return Response
     */
    public function getResponse();
} 