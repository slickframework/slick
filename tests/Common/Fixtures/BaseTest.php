<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Fixtures;

use Slick\Common\Base;

/**
 * Test class extending from Slick\Common\Base
 *
 * @package Slick\Tests\Common\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name
 * @property string $mail
 *
 * @property-write string $state
 *
 * @property-read string $fullName
 *
 * @method string getMail()
 */
class BaseTest extends Base
{

    /**
     * @readwrite
     * @var string
     */
    protected $name;

    /**
     * @readwrite
     * @var string
     */
    protected $mail;

    /**
     * @write
     * @var string
     */
    protected $state;

    /**
     * @read
     * @var string
     */
    protected $fullName;
}