<?php

/*
 * Test application configuration file
 */

return [
    // On of production, testing, development
    'environment' => 'development',
    // Router default values
    'router' => [
        'controller' => 'pages',
        'action' => 'home',
        'namespace' => 'Controllers',
        'extension' => 'html'
    ],

    // Application default paths
    'paths' => [
        'controllers' => 'Controllers',
        'models' => 'Models',
        'views' => 'Views',
    ],

    // Session configuration
    'session' => [
        'type' => "server",
        'name' => "testapp"
    ]
];
