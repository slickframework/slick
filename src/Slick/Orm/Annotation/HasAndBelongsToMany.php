<?php

/**
 * HasAndBelongsToMany annotation class
 *
 * @package   Slick\Orm\Annotation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Annotation;

use Slick\Common\Inspector\Annotation;

/**
 * HasAndBelongsToMany annotation class
 *
 * @package   Slick\Orm\Annotation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasAndBelongsToMany extends Annotation implements RelationInterface
{

    /**
     * Creates an annotation with provided data
     *
     * @param string $name
     * @param mixed $parsedData
     */
    public function __construct($name, $parsedData)
    {
        parent::__construct($name, $parsedData);
        $this->_name = 'hasAndBelongsToMany';
    }

}
