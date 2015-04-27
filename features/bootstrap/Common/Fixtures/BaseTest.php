<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Common\Fixtures;

use Slick\Common\Base;

/**
 * Class BaseTest
 *
 * @property string $name
 * @property string $mail
 *
 * @property-write string $state
 *
 * @property-read string $fullName
 *
 * @method string getMail()
 * @method boolean isAdult()
 *
 * @package Common\Fixtures
 */
class BaseTest extends Base
{

    /**
     * @readwrite
     * @var string
     */
    protected $name = 'Test';

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

    /**
     * @read
     * @var bool
     */
    protected $adult = true;
}