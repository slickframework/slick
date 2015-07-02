<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Class Boolean
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Boolean extends AbstractColumn
{

    /**
     * Sets new column with a given name
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }
}