<?php

/**
 * 
 */

namespace Slick\Http\PhpEnvironment;

trait ServerParams {

    /**
     * Returns an element from $_SERVER params.
     * 
     * @param  string $name The element name to retrieve
     * 
     * @return string The server value for the given element name or
     *  null if elemente is not found.
     */
    public function getServerParam($name)
    {
        $server = $this->getServerParams();
        if (isset($server[$name])) {
            return $server[$name];
        }
        return null;
    }
}