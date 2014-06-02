<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 5/3/14
 * Time: 12:57 AM
 */

namespace Slick\Di;


trait ContainerAwareTrait
{

    /**
     * @var Container
     */
    protected $_container;

    /**
     * Returns the internal dependency injector container
     *
     * @return Container The dependency injector
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     * Sets the dependency injector container
     *
     * @param ContainerInterface $container The injector to set
     *
     * @return Object A self instance for method chain calls
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->_container = $container;
        return $this;
    }

} 