<?php

/**
 * This file is part of slick/slick package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick;

use Slick\Common\AbstractVersion;

/**
 * slick/slick package current version
 *
 * @package Slick\Common
 */
final class Version extends AbstractVersion
{

    /**
     * @var string Version identification
     *
     * @see \Slick\Common\AbstractVersion::compare()
     */
    const VERSION = '1.2.0-dev';

    /**
     * @var string GitHub URL
     */
    const REPOSITORY_URL =
        "https://api.github.com/repos/slickframework/slick/git/refs/tags/v";
}