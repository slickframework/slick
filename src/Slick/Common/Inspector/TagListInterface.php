<?php

/**
 * TagListInterface
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Inspector;

/**
 * TagListInterface ensures that this list contains only Tag objects.
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface TagListInterface extends \ArrayAccess
{

	public function append(\Slick\Common\Inspector\Tag $tag);

}