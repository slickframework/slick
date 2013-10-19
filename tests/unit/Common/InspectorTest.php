<?php

/**
 * Inspector test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Common;

use Slick\Common\Inspector;
use Common\Examples;

/**
 * Use example Car class for tests.
 */
require_once dirname(__FILE__) . '/Examples/Car.php';

/**
 * Inspector class test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class InspectorTest extends \Codeception\TestCase\Test
{

    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    /**
     * @var \Slick\Common\Inspector The SUT object
     */
    protected $_inspector = null;

    /**
     * @var string Class comment for test
     */
    protected $_classComment = '/**
 * Car is an example class used to test the \Slick\Common\Inspector class
 *
 * @package    Test\Common\Examples
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */';

    /**
     * Sets the inspector used for tests.
     */
    public function _before()
    {
        parent::_before();
        $this->_inspector = new Inspector(new Examples\Car());
    }

    /**
     * Unsets the inspector for the next test.
     */
    public function _after()
    {
        parent::_after();
    }

    /**
     * Inspector creation test.
     * 
     * @test
     */
    public function createAnInspector()
    {
        $inspector = new Inspector('\Common\Examples\Car');
        $fromClass = new Inspector(new Examples\Car());
        $this->assertInstanceOf('\Slick\Common\Inspector', $fromClass);
        $this->assertInstanceOf('\Slick\Common\Inspector', $inspector);
        unset($inspector, $fromClass);
    }

    /**
     * Read the inspected class comment test
     * 
     * @test
     */
    public function readClassComment()
    {
        $this->assertEquals(
            $this->_classComment,
            $this->_inspector->getClassMeta()
        );
    }

}
