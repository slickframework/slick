<?php

/**
 * Slick Framework web application startup application
 *
 * @package   Tests\App
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

use Slick\Mvc\Application;
use Composer\Autoload\ClassLoader;
use Slick\Configuration\Configuration;

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set the base directory for this application
chdir(dirname(__DIR__));
$path =  dirname(dirname(getcwd()));


/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = require_once $path .'/vendor/autoload.php';

$loader = new ClassLoader();
$loader->add(null, getcwd());
$loader->register();

// Create application
Configuration::addPath(getcwd() . '/Configuration');
$app = new Application();

// Application bootstrap
$app->bootstrap();
$app->run();


$app->getResponse()->send();