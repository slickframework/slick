<?php

/**
 * ALTER TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;
use Codeception\Util\Stub;

/**
 * ALTER TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Query\Ddl\Alter
     */
    protected $_alter;

    protected function _before()
    {
        $db = new Database();
        $db = $db->initalize();
        $this->_alter = $db->connect()->ddlQuery()->alter('users');
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {

    }

}