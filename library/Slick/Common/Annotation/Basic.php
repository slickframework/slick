<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use Slick\Common\AnnotationInterface;

/**
 * Basic, general propose annotation interface implementation
 *
 * @package Slick\Common\Annotation
 */
class Basic implements AnnotationInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var mixed
     */
    protected $value = false;
    /**
     * @var array
     */
    protected $parameters = [];
    /**
     * @var array
     */
    protected $commonTags = [
        'author', 'var', 'return', 'throws', 'copyright',
        'license', 'since', 'property', 'method'
    ];

    /**
     * Creates an annotation with parsed data
     *
     * @param string $name
     * @param mixed $parsedData
     */
    public function __construct($name, $parsedData)
    {
        $this->process($name, $parsedData);
    }

    /**
     * Returns the annotation name
     *
     * @return string
     */
    public function getName()
    {
        if (is_null($this->name)) {
            $className = get_class($this);
            $this->name = $className;
            if ($className == "Slick\\Common\\Annotation\\Basic") {
                $this->name = $this->alias;
            }
        }
        return $this->name;
    }

    /**
     * Returns the value in the tag
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the value of a given parameter name
     *
     * @param string $name
     * @return null|mixed The parameter name or null if parameter is not found
     */
    public function getParameter($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }

    /**
     * Fix the parameters for string tags
     */
    protected function checkCommonTags()
    {
        if (in_array($this->getName(), $this->commonTags)) {
            $this->value = $this->parameters['raw'];
        }
    }

    /**
     * Annotation factory handler for property assignment
     *
     * @param string $alias The alias used in the comment as a tag
     * @param array|mixed $parameters The Metadata from the tag
     *
     * @return AnnotationInterface
     */
    public function process($alias, $parameters)
    {
        $this->alias = $alias;
        $this->value = $parameters;
        if (is_array($parameters)) {
            $first = reset($parameters);
            if ($first === true) {
                $this->value = key($parameters);
                array_shift($parameters);
            }
            $this->parameters = array_merge($this->parameters, $parameters);
        }
        $this->checkCommonTags();
    }
}
