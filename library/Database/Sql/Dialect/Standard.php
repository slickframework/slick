<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Class Standard
 *
 * @package Slick\Database\Sql\Dialect
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class Standard extends AbstractDialect
{

    /**
     * @var SqlTemplateInterface
     */
    protected $template;

    /**
     * @var string Template namespace
     */
    protected $namespace = '\Slick\Database\Sql\Dialect\Standard';

    /**
     * Uses for override
     * @var array A map that ties Sql classes to the correspondent template
     */
    protected $map = [];

    /**
     * Default
     * @var array A map that ties Sql classes to the correspondent template
     */
    protected $defaultMap = [
        'InsertSqlTemplate'      => 'Slick\Database\Sql\Insert',
        'SelectSqlTemplate'      => 'Slick\Database\Sql\Select',
        'UpdateSqlTemplate'      => 'Slick\Database\Sql\Update',
        'DeleteSqlTemplate'      => 'Slick\Database\Sql\Delete',
        'DropTableSqlTemplate'   => 'Slick\Database\Sql\Ddl\DropTable',
        'AlterTableSqlTemplate'  => 'Slick\Database\Sql\Ddl\AlterTable',
        'CreateTableSqlTemplate' => 'Slick\Database\Sql\Ddl\CreateTable',
        'CreateIndexSqlTemplate' => 'Slick\Database\Sql\Ddl\CreateIndex',
        'DropIndexSqlTemplate'   => 'Slick\Database\Sql\Ddl\DropIndex',
    ];

    /**
     * Updates the template map
     */
    public function __construct()
    {
        $this->map = array_replace($this->defaultMap, $this->map);
    }

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    public function getSqlStatement()
    {
        return $this->getTemplate()
            ->processSql($this->sql);
    }

    /**
     * Creates the template for current SQL Object
     *
     * @return SqlTemplateInterface
     */
    protected function getTemplate()
    {
        if (is_null($this->template)) {
            foreach ($this->map as $template => $sqlClass) {
                $templateClass = $this->namespace."\\".$template;
                if ($this->sql instanceof $sqlClass) {
                    /** @var SqlTemplateInterface $template */
                    $template = new $templateClass();
                    $this->template = $template;
                    break;
                }
            }
        }
        return $this->template;
    }
}