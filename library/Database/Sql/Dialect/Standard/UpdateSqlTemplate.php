<?php
 /**
 * UpdateSqlTemplate
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <filipe.silva@sata.pt>
 * @copyright 2014-2015 Grupo SATA
 * @since     v0.0.0
 */

namespace Slick\Database\Sql\Dialect\Standard;


use Slick\Database\Sql\DataSetInterface;
use Slick\Database\Sql\Dialect\DataAwareSqlTemplateInterface;
use Slick\Database\Sql\Update;

class UpdateSqlTemplate extends AbstractSqlTemplate
    implements DataAwareSqlTemplateInterface
{

    /**
     * @var Update
     */
    protected $sql;

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param DataSetInterface $sql
     *
     * @return string
     */
    public function processSql(DataSetInterface $sql)
    {
        $this->sql = $sql;
        $template = "UPDATE %s SET (%s) WHERE %s";
        return sprintf(
            $template,
            $this->sql->getTable(),
            $this->getFieldsAndPlaceholders(),
            $this->sql->getWhereStatement()
        );
    }

    /**
     * Creates the fields and its placeholders
     *
     * @return string
     */
    protected function getFieldsAndPlaceholders()
    {
        $fields = $this->sql->getFields();
        $parts = [];
        foreach ($fields as $field) {
            $parts[] = "{$field} = :{$field}";
        }
        return implode(', ', $parts);
    }
}