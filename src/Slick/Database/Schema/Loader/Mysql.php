<?php

/**
 * Mysql Schema loader
 *
 * @package   Slick\Database\Schema\Loader
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema\Loader;


use Slick\Database\Schema;
use Slick\Common\BaseMethods;
use Slick\Database\Schema\LoaderInterface;
use Slick\Database\Schema\SchemaInterface;

/**
 * Mysql Schema loader
 *
 * @package   Slick\Database\Schema\Loader
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends Standard implements LoaderInterface
{

    /**
     * @read
     * @var string
     */
    protected $_getTablesSql = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE='BASE TABLE' AND TABLE_SCHEMA=?";

    /**
     * @read
     * @var string
     */
    protected $_getColumnsSql = "SELECT
                  COLUMN_NAME AS columnName,
                  data_type AS type,
                  character_maximum_length AS length,
                  numeric_precision AS 'precision',
                  column_default AS 'default',
                  is_nullable AS isNullable
                FROM
                  information_schema.tables as t
                  JOIN
                  information_schema.columns AS c ON
                    t.table_catalog=c.table_catalog AND
                    t.table_schema=c.table_schema AND
                    t.table_name=c.table_name
                WHERE
                    c.table_schema=:schemaName
                  AND
                    c.table_name=:tableName";

    /**
     * @read
     * @var string
     */
    protected $_getConstraintsSql = "SELECT
             tc.CONSTRAINT_NAME AS constraintName,
             CONSTRAINT_TYPE AS constraintType,
             ccu.COLUMN_NAME AS columnName,
             ccu.REFERENCED_TABLE_NAME AS referenceTable,
             ccu.REFERENCED_COLUMN_NAME AS referenceColumn,
             rc.UPDATE_RULE AS onUpdate,
             rc.DELETE_RULE AS onDelete
            FROM
              INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
              LEFT JOIN
              INFORMATION_SCHEMA.KEY_COLUMN_USAGE ccu ON
                tc.CONSTRAINT_CATALOG=ccu.CONSTRAINT_CATALOG AND
                tc.CONSTRAINT_SCHEMA=ccu.CONSTRAINT_SCHEMA AND
                tc.CONSTRAINT_NAME=ccu.CONSTRAINT_NAME AND
                tc.TABLE_SCHEMA=ccu.TABLE_SCHEMA AND
                tc.TABLE_NAME=ccu.TABLE_NAME
              LEFT JOIN
              INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc ON
                rc.CONSTRAINT_CATALOG=ccu.CONSTRAINT_CATALOG AND
                rc.CONSTRAINT_SCHEMA=ccu.CONSTRAINT_SCHEMA AND
                rc.CONSTRAINT_NAME=ccu.CONSTRAINT_NAME
            WHERE
              tc.TABLE_SCHEMA='test' AND   -- see remark
              tc.TABLE_NAME='profiles'
            ORDER BY tc.CONSTRAINT_NAME";
}
