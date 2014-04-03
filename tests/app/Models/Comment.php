<?php

/**
 * Comment model
 */

namespace Models;

use Slick\Mvc\Model;

/**
 * Class Comment
 * @package Models
 */
class Comment extends Model
{
    /**
     * @readwrite
     * @column type=integer, primaryKey, autoIncrement
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=big
     * @validate notEmpty, text
     * @filter text
     * @var string
     * @display
     */
    protected $_body;

    /**
     * @readwrite
     * @belongsTo Models\Post
     * @var Post
     */
    protected $_post;

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