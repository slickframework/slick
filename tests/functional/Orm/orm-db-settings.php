<?php

/**
 * Database settings for Orm entity functional tests
 */

return [
    'orm-db' => array(
        'driver' => 'Mysql',
        'options' => [
            'host' => 'localhost',
            'username' => 'travis',
            'database' => 'slick_tests'
        ]
    )
];