<?php

/**
 * Template
 *
 * @package   Slick\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template;

use Slick\Common\Base;

/**
 * Template
 *
 * @package   Slick\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Template extends Base
{
    /**
     * @readwrite
     * @var string The engine to use
     */
    protected $_engine = 'Twig';

    /**
     * @readwrite
     * @var array Options for template initializing
     */
    protected $_options = array();

    /**
     * Initializes the engine
     * 
     * @return EngineInterface
     */
    public function initialize()
    {
        $engine = null;

        if (class_exists($this->_engine)) {
            $name = $this->_engine;
            $engine = new $name($this->_options);
            if (!is_a($engine, 'Slick\Template\EngineInterface')) {
                throw new Exception\InvalidArgumentException(
                    "'{$name}' is not an implementation of " .
                    "Slick\Template\EngineInterface"
                );
                
            }
            return $engine;
        }

        switch (strtolower($this->_engine)) {
            case 'twig':
                $engine = new Engine\Twig($this->options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "Template {$this->engine} is unknown."
                );
                
        }
        return $engine;
    }
}