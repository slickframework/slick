<?php

/**
 * AbstractEngine 
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template\Engine;

use Slick\Common\Base,
    Slick\Template\EngineInterface;

/**
 * AbstractEngine 
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractEngine extends Base implements EngineInterface
{

    /**
     * Handles the initialization if engine is already initialized
     * 
     * @return AbstractEngine
     */
    public function initialize()
    {
        return $this;
    }
}