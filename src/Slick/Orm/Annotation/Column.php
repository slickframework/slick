<?php

/**
 * Column annotation class
 *
 * @package   Slick\Orm\Annotation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Annotation;

use Slick\Common\Inspector\Annotation;

/**
 * Column annotation class
 *
 * @package   Slick\Orm\Annotation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $field
 */
class Column extends Annotation
{

    /**
     * @readwrite
     * @var string
     */
    protected $_field;
    /**
     * Creates an annotation with provided data
     *
     * @param string $name
     * @param mixed $parsedData
     */
    public function __construct($name, $parsedData)
    {
        parent::__construct($name, $parsedData);
        $this->_name = 'column';
    }
}
