<?php

/**
 * Column
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;
use Slick\Common\Base;
use Slick\Common\Inspector\AnnotationsList;
use Slick\Common\Inspector\TagList;

/**
 * Column
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Column extends Base
{

    /**
     * @readwrite
     * @var string property name
     */
    protected $_raw;

    /**
     * @readwrite
     * @var string field name
     */
    protected $_name;

    /**
     * @readwrite
     * @var boolean Primary field name
     */
    protected $_primaryKey;

    /**
     * @readwrite
     * @var string data type
     */
    protected $_type;

    /**
     * @readwrite
     * @var string data size keyword
     */
    protected $_size;

    /**
     * @readwrite
     * @var integer data length
     */
    protected $_length;

    /**
     * @readwrite
     * @var boolean used as index flag
     */
    protected $_index;

    /**
     * @readwrite
     * @var boolean data is readable
     */
    protected $_read;

    /**
     * @readwrite
     * @var boolean data is writable
     */
    protected $_write;

    /**
     * @readwrite
     * @var string validation definitions
     */
    protected $_validate;

    /**
     * @readwrite
     * @var string label used in forms
     */
    protected $_label;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_unsigned;

    /**
     * Parses metadata to create a column
     *
     * @param AnnotationsList $metaData Property meta data
     * @param string  $property Property name
     *
     * @return Column It wil return a column object or a boolean false it the
     *  meta data does not refer to a column definition
     */
    public static function parse($metaData, $property)
    {
        if (!$metaData->hasAnnotation("@column")) {
            return false;
        }
        $columnAnnotation = $metaData->getAnnotation("@column");
        return new Column(
            [
                'raw' => $property,
                'name' => preg_replace('#^_#', '', $property),
                'primaryKey' => $columnAnnotation->getParameter('primary'),
                'unsigned' => $columnAnnotation->getParameter('unsigned'),
                'type' => $columnAnnotation->getParameter('type'),
                'length' => $columnAnnotation->getParameter('length'),
                'size' => $columnAnnotation->getParameter('size'),
                'index' => $columnAnnotation->getParameter('index'),
                'write' =>
                    $metaData->hasAnnotation("@readwrite") ||
                    $metaData->hasAnnotation("@write"),
                'read' =>
                    $metaData->hasAnnotation("@readwrite") ||
                    $metaData->hasAnnotation("@read"),
                'validate' => ($metaData->hasAnnotation('@validate')) ?
                        $metaData->getAnnotation("@validate")->getParameter('_raw'):null,
                'label' => ($metaData->hasAnnotation('@label')) ?
                        $metaData->getAnnotation("@label")->getParameter('_raw'):null
            ]
        );
    }
} 