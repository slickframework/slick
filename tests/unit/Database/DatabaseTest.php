<?php

/**
 * Database test case
 * 
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database;

use Slick\Database\Database;

/**
 * DatabaseTest test case
 * 
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DatabaseTest extends \Codeception\TestCase\Test
{

    /**
     * Test for not implemented method exception
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function initializeWithInvalidType()
    {
        $db = new Database();
        $db->initialize();
    }

    /**
     * Test connector initializarion
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function initializeDatabase()
    {
        $db = new Database(array('type' => 'mysql'));
        $conn1 = $db->initialize();
        $this->assertInstanceOf('\Slick\Database\Connector', $conn1);
        $this->assertInstanceOf('\Slick\Database\Connector\Mysql', $conn1);

        $db->type = 'unknown';
        $db->initialize();
    }

}