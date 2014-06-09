<?php

/**
 * Column Annotations
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;

use Slick\Common\Inspector\Annotation;
use Slick\Common\Inspector\AnnotationInterface;

/**
 * Column Annotations
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ColumnAnnotation extends Annotation implements AnnotationInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $_parameters = [
        'primaryKey' => false,
        'type' => null,
        'size' => null,
        'unsigned' => false,
        'length' => null,
        'index' => false
    ];
} 