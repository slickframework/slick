<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Exception;

use LogicException;
use Slick\Common\Exception;

/**
 * Class used when an annotation tag for a class that does not implement the
 * annotation interface is used in a doc block.
 *
 * @package Slick\Common\Exception
 */
class InvalidAnnotationClassException extends LogicException implements
    Exception
{

}
