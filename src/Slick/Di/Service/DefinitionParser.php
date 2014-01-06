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
    Slick\Di\ServiceInterface,
    Slick\Exception;

/**
 * DefinitionParser
 *
 * @package   Slick\Di\Service
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionParser extends Base
{

    /**
     * @write
     * @var ServiceInterface
     */
    protected $_service = null;

    /**
     * @write
     * @var mixed
     */
    protected $_definition = null;

    /**
     * Parses a service definition
     * 
     * @param  mixed            $definition The service definition
     * @param  ServiceInterface $service    The service itself
     * 
     * @return void
     */
    public static function parse($definition, ServiceInterface &$service)
    {
        $parser = new Static();
        $parser->_service = $service;
        $parser->_definition = $definition;
        $parser->_evaluate();
        $service = $parser->_service;
    }

    /**
     * Evaluates the definition to determine the correct parser
     */
    protected function _evaluate()
    {
        if (is_callable($this->_definition)) {
            return $this->_parseCallable();
        }

        if (is_string($this->_definition)) {
            return $this->_parseClassName();
        }

        if (is_array($this->_definition)) {
            return $this->_parseArray();
        }        
    }

    /**
     * Parses callable definition
     */
    protected function _parseCallable()
    {
        if (is_a($this->_definition, 'Closure')) {
            $this->_service->closure = true;
            return;
        }
        $this->_service->callable = true;
    }

    /**
     * Parses class name
     */
    protected function _parseClassName()
    {
        $this->_service->className = $this->_definition;
    }

    /**
     * Parses the array
     */
    protected function _parseArray()
    {
        $def = $this->_definition;
        if (isset($def['className'])) {
            $this->_service->className = $def['className'];
        }

        if (isset($def['arguments'])) {
            $this->_service->arguments = $def['arguments'];
        }

        if (isset($def['calls'])) {
            $this->_service->calls = $def['calls'];
        }

        if (isset($def['properties'])) {
            $this->_service->properties = $def['properties'];
        }
    }
}