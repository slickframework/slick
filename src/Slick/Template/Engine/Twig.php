<?php

/**
 * Twig
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template\Engine;

use Slick\Template\EngineInterface;
use Twig_Environment,
    Twig_Loader_Filesystem,
    Twig_Error;
use Slick\Template\Exception;

/**
 * Twig
 *
 * @package   Slick\Template\Engine
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Twig extends AbstractEngine
{

    /**
     * @read
     * @var string The template source file.
     */
    protected $_source;

    /**
     * @readwrite
     * @var array The list of paths where to find template fields
     * order maters.
     */
    protected $_paths = array();

    /**
     * @readwrite
     * @var Twig_Environment Twig engine
     */
    protected $_twig;

    /**
     * Parses the source template code.
     *
     * @param string $source The template to parse
     *
     * @return EngineInterface Returns this instance for chaining methods calls
     */
    public function parse($source)
    {
        $this->_source = $source;
    }

    /**
     * Processes the template with data to produce the final output.
     *
     * @param mixed $data The data that will be used to process the view.
     *
     * @throws \Slick\Template\Exception\ParserException
     *
     * @return string Returns processed output string.
     */
    public function process($data = array())
    {
        try {
            return $this->twig->render($this->_source, $data);
        } catch (Twig_Error $exp) {
            throw new Exception\ParserException(
                "Error Processing Request: " . $exp->getMessage()
            );

        }
    }

    /**
     * Lazy loading of twig library
     *
     * @return Twig_Loader_Filesystem
     */
    public function getTwig()
    {
        if (is_null($this->_twig)) {
            $this->_twig = new Twig_Environment(
                new Twig_Loader_Filesystem($this->_paths)
            );
        }
        return $this->_twig;
    }
}