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
        $this->alias = $name;
        $this->value = $parsedData;
        if (is_array($parsedData)) {
            $first = reset($parsedData);
            if ($first === true) {
                $this->value = key($parsedData);
                array_shift($parsedData);
            }
            $this->parameters = array_merge($this->parameters, $parsedData);
        }
        $this->checkCommonTags();
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
}
