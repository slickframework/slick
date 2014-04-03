<?php

/**
 * Exception meta data evaluator utility class
 *
 * @package   Slick\Mvc\Exception\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception\Utils;

use Slick\Utility\Text,
    Slick\Common\Base;
use Exception;

/**
 * Exception meta data evaluator utility class
 *
 * @package   Slick\Mvc\Exception\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ExceptionData extends Base
{

    /**
     * @readwrite
     * @var Exception
     */
    protected $_exception;

    /**
     * @readwrite
     * @var string Current exception name
     */
    protected $_exceptionName;

    /**
     * Overrides default constructor to for exception assignment
     *
     * @param Exception $exp
     * @param array $options
     */
    public function __construct(Exception $exp, $options = [])
    {
        parent::__construct($options);
        $this->_exception = $exp;
    }

    /**
     * Returns exception name
     *
     * @return string
     */
    public function getExceptionName()
    {
        if (is_null($this->_exceptionName)) {
            $parts = explode('\\', get_class($this->_exception));
            $name = end($parts);
            $this->_exceptionName = Text::camelCaseToSeparator($name);
        }
        return $this->_exceptionName;
    }

    /**
     * Returns a printable versions of the stack trace
     *
     * @return string
     */
    public function getStackTrace()
    {
        $str = $this->_exception->getTraceAsString();
        $str = str_replace(getcwd().'/', '', $str);
        return preg_replace('/(\/.*src\/Slick)/i', 'Slick', $str);
    }
} 