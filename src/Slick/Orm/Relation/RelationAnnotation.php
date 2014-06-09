<?php

/**
 * Relation Annotation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

use Slick\Common\Inspector\Annotation;

/**
 * Relation Annotation
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RelationAnnotation extends Annotation
{

    /**
     * @var array default parameters
     */
    protected $_parameters = [
        'foreignKey' => null,
        'dependent' => true,
        'type' => 'LEFT',
        'joinTable' => null,
        'limit' => 25,
        'associationForeignKey' => null
    ];
} 