<?php

/**
 * Connector
 *
 * @package   Codeception\Util\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Codeception\Util\Connector;

use Slick\Configuration\Configuration;
use Slick\Mvc\Application;
use Slick\Template\Template;
use Symfony\Component\BrowserKit\Client;
use Composer\Autoload\ClassLoader;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\BrowserKit\Request;

use Zend\Http\Request as HttpRequest;
use Zend\Stdlib\Parameters;
use Zend\Uri\Http as HttpUri;

/**
 * Connector
 *
 * @package   Codeception\Util\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SlickConnector extends Client
{
    /**
     * Makes a request.
     *
     * @param Request $request An origin request instance
     *
     * @return object An origin response instance
     */
    protected function doRequest($request)
    {
        $path = getcwd();
        if (defined('APP_PATH')) {
            $path = APP_PATH;
        }
        $loader = new ClassLoader();

        $loader->add(null, $path);
        $loader->register();

        // Create application
        Configuration::addPath($path . '/Configuration');
        Template::addPath($path . '/View');

        $uri = str_replace('http://localhost/', '', $request->getUri());
        preg_match('/^([a-zA-Z0-9\/\-_]+).?([a-zA-Z0-9\-_]*)\??([a-zA-Z0-9\-_\=]*)/i', $uri, $matches);

        if (isset($matches[3]) && $matches[3] != '') {
            $params = parse_url($request->getUri());
            parse_str($params['query'], $_GET);
        }

        if (isset($matches[1]) && $matches[1] != '') {
            $_GET['url'] = $matches[1];
        }

        if (isset($matches[2]) && $matches[2] != '') {
            $_GET['extension'] = $matches[2];
        }

        $app = new Application();

        $zendRequest = $app->getRequest();
        $zendResponse = $app->getResponse();

        $uri = new HttpUri($request->getUri());
        $queryString = $uri->getQuery();
        $method = strtoupper($request->getMethod());

        if ($queryString) {
            parse_str($queryString, $query);
        }

        if ($method == HttpRequest::METHOD_POST) {
            $zendRequest->setPost(new Parameters($request->getParameters()));
        }
        /* elseif ($method == HttpRequest::METHOD_PUT) {
            $zendRequest->setContent($request->getContent());
        }*/

        $zendRequest->setMethod($method);
        $zendRequest->setUri($uri);

        // Application bootstrap
        $app->bootstrap();

        $app->run();


        return new Response(
            $zendResponse->getContent(),
            $zendResponse->getStatusCode(),
            $zendResponse->getHeaders()->toArray()
        );

    }
}