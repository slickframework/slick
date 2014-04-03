<?php

// This is global bootstrap for autoloading

$projectRoot = dirname(dirname(__FILE__));
$loader = include($projectRoot . '/vendor/autoload.php');

if (!class_exists('Slick\Common\Base')) {
    $loader->add('Slick', $projectRoot . '/src');
    $loader->register();
}

define('APP_PATH', __DIR__ . '/app');

include_once(getcwd().'/src/Slick/Mvc/Codeception/Module.php');

