<?php

/**
 * Application
 *
 * @package   Slick\Console
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * Application
 *
 * @package   Slick\Console
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Application extends SymfonyApplication
{

    /**
     * @var string[]
     */
    protected $_paths = [
        'Slick\Mvc\Command',
    ];

    /**
     * Load Slick Framework command from paths
     */
    public function loadSlickCommands()
    {

    }
}