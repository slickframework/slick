<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Slick\Common\BaseMethods;
use Slick\Di\Definition\Alias;
use Slick\Di\Exception\InvalidArgumentException;

/**
 * ContainerBuilder for dependency container creation
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @method array getDefinitions() Returns the source definitions
 */
final class ContainerBuilder
{

    /**
     * For easy getters and setters
     */
    use BaseMethods;

    /**
     * @read
     * @var array
     */
    protected $definitions = [];

    /**
     * @read
     * @var bool
     */
    protected $override = false;

    /**
     * @read
     * @var Container
     */
    protected $container;

    /**
     * Creates a container builder with provided definitions and override mode
     *
     * @param array|string $definitions
     *    The definitions list for container creation
     * @param bool         $override
     *    Set to true to override existing definitions
     */
    public function __construct($definitions, $override = false)
    {
        $this->definitions = $this->checkDefinitions($definitions);
        $this->override = $override;
    }

    /**
     * Creates a container with existing definitions
     *
     * @return Container
     */
    public function getContainer()
    {
        if (is_null($this->container)) {
            $this->container = new Container();
            $this->applyDefinitions();
        }
        return $this->container;
    }

    /**
     * Apply definitions to content
     */
    private function applyDefinitions()
    {
        foreach($this->definitions as $name => $entry) {
            if (
                is_string($entry) &&
                preg_match('/^@(?P<key>.*)$/i', $entry, $result)
            ) {
                $entry = new Alias(['target' => $result['key']]);
            }

            if (!$this->container->has($name) || $this->override) {
                $this->container->register($name, $entry);
            }
        }
    }

    /**
     * Checks the data to be returned
     *
     * @param string|array $definitions
     * @return array
     */
    private function checkDefinitions($definitions)
    {
        if (is_array($definitions)) {
            return $definitions;
        }

        if (is_string($definitions) && file_exists($definitions)) {
            $data = include($definitions);
            return $this->checkDefinitions($data);
        }

        throw new InvalidArgumentException(
            "Definitions file not found or invalid. Cannot create ".
            "container builder."
        );
    }
}
