<?php
/**
 * InvalidArgumentException
 *
 * @package   Slick\Orm\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Exception;

use RuntimeException;
use Slick\Orm\Exception as OrmException;

/**
 * InvalidArgumentException
 *
 * @package   Slick\Orm\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends RuntimeException implements OrmException
{

} 