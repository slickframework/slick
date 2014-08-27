<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 8/26/14
 * Time: 6:49 PM
 */

namespace Slick\Orm\Annotation;


use Slick\Common\Inspector\Annotation;

class Column extends Annotation
{

    public function __construct($name, $parsedData)
    {
        parent::__construct($name, $parsedData);
        $this->_name = 'column';
    }
} 