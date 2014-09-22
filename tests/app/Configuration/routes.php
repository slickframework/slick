<?php

/**
 * Routes configuration file
 * @var \Slick\Mvc\Router $router
 */

$GLOBALS['routeTest'] = true;

$router->map('[:controller]?/?[:action]?/?[**:trailing]?', [], 'default');