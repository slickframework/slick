<?php

/**
 * Post model
 */

namespace Models;

use Slick\Mvc\Model;

/**
 * Class Post
 * @package Models
 */
class Post extends Model
{
    /**
     * @readwrite
     * @column type=integer, primaryKey, autoIncrement
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=tiny
     * @validate notEmpty
     * @var string
     */
    protected $_title;

    /**
     * @readwrite
     * @column type=text, size=big
     * @filter text, htmlEntities
     * @var string
     */
    protected $_body;

    /**
     * @readwrite
     * @column type=datetime
     * @var string
     */
    protected $_published;

    /**
     * @var Comment[]
     */
    protected $_comments;

    /**
     * @readwrite
     * @var string Data source configuration name
     */
    protected $_dataSourceName = 'functional';

    /**
     * @readwrite
     * @var string
     */
    protected $_configFile = 'test_database';
} 