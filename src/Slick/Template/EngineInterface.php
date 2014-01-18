<?php

/**
 * EngineInterface
 *
 * @package   Slick\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template;

/**
 * EngineInterface
 *
 * @package   Slick\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface EngineInterface
{

    /**
     * Parses the source template code.
     *
     * @param string $source The template to parse
     * 
     * @return EngineInterface Returns this instance for chainnig methods calls
     */
    public function parse($source);

    /**
     * Processes the template with data to produce the final output.
     *
     * @param mixed $data The data that will be used to process the view.
     * 
     * @return string Returns processed output string.
     */
    public function process($data = array());
}