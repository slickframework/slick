<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

use ReflectionClass;
use Slick\Database\Exception\InvalidArgumentException;
use Slick\Database\Sql\Dialect\DialectInterface;

/**
 * Sql Dialect factory class
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Dialect
{

    /**#@+
     * @var string Available dialects
     */
    const STANDARD = 'standard';
    const MYSQL    = 'mysql';
    const SQLITE   = 'sqlite';
    /**#@-*/

    /**
     * @var array A map for known dialect classes
     */
    private static $map = [
        self::STANDARD => 'Slick\Database\Sql\Dialect\Standard',
        self::MYSQL    => 'Slick\Database\Sql\Dialect\Mysql',
        self::SQLITE   => 'Slick\Database\Sql\Dialect\Sqlite',
    ];

    /**
     * @var string Dialect interface to check
     */
    const DIALECT_INTERFACE = 'Slick\Database\Sql\Dialect\DialectInterface';

    /**
     * Creates a dialect with provided SQL object
     *
     * You can use the known dialects such as {@see Dialect::MYSQL} or you can
     * create your custom dialect.
     *
     * The custom dialect class must implement the
     * {@see \Slick\Database\Sql\Dialect\DialectInterface} interface or an
     * exception will be thrown when trying to create it.
     *
     * @param string $dialect Dialect name or dialect class name.
     * @param SqlInterface $sql
     *
     * @throws InvalidArgumentException
     *
     * @return DialectInterface
     */
    public static function create($dialect, SqlInterface $sql)
    {
        if (array_key_exists($dialect, self::$map)) {
            $dialect = self::$map[$dialect];
        }

        if (class_exists($dialect)) {
            $interface = self::DIALECT_INTERFACE;
            if (in_array($interface, class_implements($dialect))) {
                return self::createDialect($dialect, $sql);
            }

            throw new InvalidArgumentException(
                "The class {$dialect} does not implements the ".
                "{$interface} interface."
            );
        }
        throw new InvalidArgumentException(
            "Trying to create an unknown dialect. '{$dialect}' is".
            " not recognized."
        );
    }

    /**
     * Creates the dialect object with the given class name
     *
     * @param string $class
     * @param SqlInterface $sql
     *
     * @return DialectInterface
     */
    private static function createDialect($class, SqlInterface $sql)
    {
        $reflection = new ReflectionClass($class);
        /** @var DialectInterface $dialect */
        $dialect = $reflection->newInstanceArgs();
        $dialect->setSql($sql);
        return $dialect;
    }
}