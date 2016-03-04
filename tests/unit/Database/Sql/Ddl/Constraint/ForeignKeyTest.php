<?php

/**
 * Foreign Key constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;

/**
 * Foreign Key constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ForeignKeyTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to create a foreign key constraint
     * @test
     */
    public function createForeignKeyConstraint()
    {
        $frk = new ForeignKey('profileFk', 'profile_id', 'profiles', 'id', [
            'onDelete' => ForeignKey::CASCADE
        ]);
        $this->assertEquals('profileFk', $frk->getName());
        $this->assertEquals('profile_id', $frk->getColumn());
        $this->assertEquals('profiles', $frk->getReferenceTable());
        $this->assertEquals('id', $frk->getReferenceColumn());
        $this->assertEquals('NO ACTION', $frk->getOnUpdate());
        $this->assertEquals('CASCADE', $frk->getOnDelete());

        $instance = 'Slick\Database\Sql\Ddl\Constraint\ForeignKey';

        $this->assertInstanceOf($instance, $frk->setOnUpdate(ForeignKey::RESTRICTED));
        $this->assertEquals(ForeignKey::RESTRICTED, $frk->getOnUpdate());

        $this->assertInstanceOf($instance, $frk->setOnDelete(ForeignKey::SET_DEFAULT));
        $this->assertEquals(ForeignKey::SET_DEFAULT, $frk->getOnDelete());
    }
}
