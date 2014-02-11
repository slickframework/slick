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
     * @param TagList $metaData Property meta data
     * @param string  $property Property name
     *
     * @return Column It wil return a column object or a boolean false it the
     *  meta data does not refer to a column definition
     */
    public static function parse($metaData, $property)
    {
        if (!$metaData->hasTag("@column")) {
            return false;
        }

        return new Column(
            [
                'raw' => $property,
                'name' => preg_replace('#^_#', '', $property),
                'primaryKey' => (is_object($metaData->getTag("@column")->value)) ?
                    $metaData->getTag("@column")->value->check('primary') :
                    false,
                'unsigned' => (is_object($metaData->getTag("@column")->value)) ?
                    $metaData->getTag("@column")->value->check('unsigned') :
                    false,
                'type' => (isset($metaData->getTag("@column")->value['type'])) ?
                    $metaData->getTag("@column")->value['type'] :
                    null,
                'length' => (isset($metaData->getTag("@column")->value['length'])) ?
                    $metaData->getTag("@column")->value['length'] :
                    null,
                'size' => (isset($metaData->getTag("@column")->value['size'])) ?
                    $metaData->getTag("@column")->value['size'] :
                    null,
                'index' => $metaData->hasTag("@index"),
                'write' =>
                    $metaData->hasTag("@readwrite") ||
                    $metaData->hasTag("@write"),
                'read' =>
                    $metaData->hasTag("@readwrite") ||
                    $metaData->hasTag("@read"),
                'validate' => $metaData->getTag("@validate"),
                'label' => $metaData->getTag("@label")
            ]
        );
    }
} 