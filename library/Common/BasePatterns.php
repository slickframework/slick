<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

/**
 * Base methods discover patterns
 *
 * @package Slick\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class BasePatterns
{

    /**
     * @var array List of method names anf their regexp
     */
    public static $patterns = [
        'getter' => '^get([a-zA-Z0-9\_]+)$',
        'setter' => '^set([a-zA-Z0-9\_]+)$',
        'is' => '^is([a-zA-Z0-9\_]+)$'
    ];
}
