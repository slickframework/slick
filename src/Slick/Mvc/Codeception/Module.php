<?php

/**
 * Slick
 *
 * @package   Codeception\Module
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Codeception\Module;

require_once(__DIR__ .'/Connector.php');

use Codeception\Util\Framework,
    Codeception\TestCase,
    Codeception\Util\Connector\SlickConnector;

/**
 * Slick (Codeception module)
 *
 * @package   Codeception\Module
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Slick extends Framework
{

    /**
     * Runs before each test
     *
     * @param TestCase $test
     */
    public function _before(TestCase $test)
    {
        $this->client = new SlickConnector();
    }

    /**
     * Runs after each test
     *
     * @param TestCase $test
     */
    public function _after(TestCase $test)
    {
        $_SESSION = array();
        $_GET = array();
        $_POST = array();
        $_COOKIE = array();
        parent::_after($test);
    }
} 