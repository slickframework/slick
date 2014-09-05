<?php

/**
 * Abstract single relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Relation;

/**
 * Abstract single relation
 *
 * @package   Slick\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property bool $lazyLoad Flag for lazy loading of related record
 *
 * @method AbstractSingleRelation isLazyLoad() Returns lazy load status flag
 */
abstract class AbstractSingleRelation extends AbstractRelation
{

    /**
     * @readwrite
     * @var bool
     */
    protected $_lazyLoad = false;
}
