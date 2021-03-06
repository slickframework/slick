<?php

/**
 * Database configuration file
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

return [

    'unit-test' => [
        'driver' => 'Sqlite',
        'options' => [
            'file' => 'tmp.db'
        ]
    ]
];
