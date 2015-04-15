<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Annotation;

use ReflectionClass;

/**
 * Creates annotations from comment texts
 *
 * @package Slick\Common\Annotation
 */
class Factory
{

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * Creates a list of annotations from provided comment text
     *
     * @param string $comment
     * @return AnnotationList
     */
    public function getAnnotationsFor($comment)
    {
        $data = $this->getParser()
            ->setComment($comment)
            ->getAnnotationData();

        $list = new AnnotationList();
        foreach ($data as $name => $parsed) {
            $list->append(new Basic($name, $parsed));
        }
        return $list;
    }

    /**
     * Sets comment tag parser
     *
     * @param Parser $parser
     *
     * @return AnnotationList
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
        return $this;
    }

    /**
     * Retrieves current parser comment parser
     *
     * @return Parser
     */
    public function getParser()
    {
        if (is_null($this->parser)) {
            $this->setParser(new Parser());
        }
        return $this->parser;
    }

    /**
     * Sets class reflection object
     *
     * @param ReflectionClass $reflection
     * @return Factory
     */
    public function setReflection($reflection)
    {
        $this->reflection = $reflection;
        return $this;
    }


}