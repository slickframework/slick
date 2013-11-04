<?php

// This is global bootstrap for autoloading

$projectRoot = dirname(dirname(dirname(__FILE__)));
$loader = $projectRoot . '/vendor/autoload.php';

if (!class_exists('Slick\Common\Base')) {
    $loader->add('Slick', $projectRoot . '/src');
    $loader->register();
} 
