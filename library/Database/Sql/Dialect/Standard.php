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
    protected $namespace = '\Slick\Database\Sql\Dialect';

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
        'Standard\InsertSqlTemplate'      => 'Slick\Database\Sql\Insert',
        'Standard\SelectSqlTemplate'      => 'Slick\Database\Sql\Select',
        'Standard\UpdateSqlTemplate'      => 'Slick\Database\Sql\Update',
        'Standard\DeleteSqlTemplate'      => 'Slick\Database\Sql\Delete',
        'Standard\DropTableSqlTemplate'
            => 'Slick\Database\Sql\Ddl\DropTable',
        'Standard\AlterTableSqlTemplate'
            => 'Slick\Database\Sql\Ddl\AlterTable',
        'Standard\CreateTableSqlTemplate'
            => 'Slick\Database\Sql\Ddl\CreateTable',
        'Standard\CreateIndexSqlTemplate'
            => 'Slick\Database\Sql\Ddl\CreateIndex',
        'Standard\DropIndexSqlTemplate'
            => 'Slick\Database\Sql\Ddl\DropIndex',
    ];

    /**
     * Updates the template map
     */
    public function __construct()
    {
        $map = array_replace(
            array_flip($this->defaultMap),
            array_flip($this->map)
        );
        $this->map = array_flip($map);
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