<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Constraint;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;

/**
 * Class ForeignKeyTest
 *
 * @package Slick\Tests\Database\Sql\Ddl\Constraint
 * @author  Filipe Sivla <silvam.filipe@gmail.com>
 */
class ForeignKeyTest extends TestCase
{
    /**
     * @var ForeignKey
     */
    protected $foreignKey;

    /**
     * Creates the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->foreignKey = new ForeignKey(
            'profileFk',
            'profile_id',
            'profiles',
            'id',
            ['onDelete' => ForeignKey::CASCADE]
        );
    }

    /**
     * Clear for next test
     */
    protected function tearDown()
    {
        $this->foreignKey = null;
        parent::tearDown();
    }

    public function testGetName()
    {
        $this->assertEquals('profileFk', $this->foreignKey->getName());
    }

    public function testGetColumn()
    {
        $this->assertEquals('profile_id', $this->foreignKey->getColumn());
    }

    public function testSetOnDelete()
    {
        $this->assertSame(
            $this->foreignKey,
            $this->foreignKey->setOnDelete(ForeignKey::SET_DEFAULT)
        );
    }

    public function testGetOnDelete()
    {
        $this->assertEquals('CASCADE', $this->foreignKey->getOnDelete());
    }

    public function testGetOnUpdate()
    {
        $result = $this->foreignKey->setOnUpdate(ForeignKey::RESTRICTED);
        $this->assertEquals(
            ForeignKey::RESTRICTED,
            $this->foreignKey->getOnUpdate()
        );
        $this->assertSame($this->foreignKey, $result);
    }

    public function testGetReferenceTable()
    {
        $this->assertEquals(
            'profiles',
            $this->foreignKey->getReferenceTable()
        );
    }

    public function testGetReferenceColumn()
    {
        $this->assertEquals('id', $this->foreignKey->getReferenceColumn());
    }
}
