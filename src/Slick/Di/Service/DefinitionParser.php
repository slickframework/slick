<?php

/**
 * DefinitionParser
 *
 * @package   Slick\Di\Service
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Service;

use Slick\Common\Base,
    Slick\Di\ServiceInterface;

/**
 * DefinitionParser
 *
 * @package   Slick\Di\Service
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionParser extends Base
{
    protected $_service = null;

    protected $_definition = null;

    public static function parse($definition, ServiceInterface &$service)
    {
        $parser = new Static();
        $parser->_service = $service;
        $parser->_definition = $definition;
        return $parser->_evaluate();
    }

    protected function _evaluate()
    {
        if (is_object($this->_definition)) {
            return $this->_parseObject();
        }

        if (is_string($this->_definition)) {
            return $this->_parseClassName();
        } 

        return $this->_parseArray();

    }
}