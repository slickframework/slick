<?php

/**
 * Blob column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Blob column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Blob extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $_nullable = false;

    /**
     * @var int
     */
    protected $_length;

    /**
     * @var mixed
     */
    protected $_default = null;

    /**
     * Creates a blob field with given name and length
     *
     * @param string $name
     * @param int $length
     * @param array $options
     */
    public function __construct($name, $length, array $options = [])
    {
        $options['length'] = $length;
        parent::__construct($name, $options);
    }
}
