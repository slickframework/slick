<?php

/**
 * Sql Dialect factory class
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

use ReflectionClass;
use Slick\Database\Sql\Dialect\DialectInterface;
use Slick\Database\Exception\InvalidArgumentException;

/**
 * Sql Dialect factory class
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
final class Dialect
{

    /**#@+
     * Available dialects
     * @var string
     */
    const STANDARD = 'standard';
    const MYSQL    = 'mysql';

    /**
     * @var string Dialect interface to check
     */
    const DIALECT_INTERFACE = 'Slick\Database\Sql\Dialect\DialectInterface';

    /**
     * @var array A map for known dialect classes
     */
    private static $_map = [
        self::STANDARD => 'Slick\Database\Sql\Dialect\Standard',
        self::MYSQL => 'Slick\Database\Sql\Dialect\Mysql',
    ];

    /**
     * Creates a dialect with provided SQL object
     *
     * You can use the known dialects such as Dialect::MYSQL or you can create
     * your custom dialect. The custom dialect class must implement the
     * Slick\Database\Sql\Dialect\DialectInterface or an exception will be
     * thrown when trying to create it.
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
        if (array_key_exists($dialect, static::$_map)) {
            return static::_createDialect(static::$_map[$dialect], $sql);
        }

        if (class_exists($dialect)) {
            if (
                in_array(static::DIALECT_INTERFACE, class_implements($dialect))
            ) {
                return static::_createDialect($dialect, $sql);
            }
            $interface = static::DIALECT_INTERFACE;
            throw new InvalidArgumentException(
                "The class {$dialect} does not implements the " .
                "{$interface} interface."
            );
        }

        throw new InvalidArgumentException(
            "Trying to create an unknown dialect. '{$dialect}' is not recognized."
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
    private static function _createDialect($class, SqlInterface $sql)
    {
        $reflection = new ReflectionClass($class);
        /** @var DialectInterface $dialect */
        $dialect = $reflection->newInstanceArgs();
        $dialect->setSql($sql);
        return $dialect;
    }
} 