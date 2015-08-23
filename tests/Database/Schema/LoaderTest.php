<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Schema;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Schema\Loader;
use Slick\Database\Sql\Dialect;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * Schema loader factory test case
 *
 * @package Slick\Tests\Database\Schema
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class LoaderTest extends TestCase
{

    public function testCreateLoader()
    {
        $loader = new Loader();
        $loader = $loader->initialize();
        $this->assertInstanceOf('Slick\Database\Schema\LoaderInterface', $loader);
    }

    public function testCreateFromClassName()
    {
        $loader = new Loader(['className' => 'Slick\Database\Schema\Loader\Sqlite']);
        $loader = $loader->initialize();
        $this->assertInstanceOf('Slick\Database\Schema\Loader\Sqlite', $loader);
    }

    public function testCreationFromAdapterDialect()
    {
        $adapter = new CustomAdapter(['dialect' => Dialect::MYSQL]);
        $loader = new Loader(['adapter' => $adapter]);
        $loader = $loader->initialize();
        $this->assertInstanceOf('Slick\Database\Schema\Loader\Mysql', $loader);
        $this->assertSame($adapter, $loader->getAdapter());
    }

    public function testExceptionOnClassIsNotALoader()
    {
        $this->setExpectedException('Slick\Database\Exception\InvalidSchemaLoaderClass');
        new Loader(['className' => 'stdClass']);
    }
}
