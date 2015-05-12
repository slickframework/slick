<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Fixtures;

use Slick\Database\Sql\SqlInterface;

/**
 * Class Custom Query
 *
 * @package Slick\Tests\Database\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CustomQuery implements SqlInterface
{

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        return "This is a test";
    }
}