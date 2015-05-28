<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\WhereMethods;

/**
 * Where clause related methods trait test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class WhereMethodsTest extends TestCase
{

    /**
     * Import Trait
     */
    use WhereMethods;

    public function testEmptyWhereClause()
    {
        $this->assertNull($this->getWhereStatement());
    }

    public function testSimpleWhere()
    {
        $this->andWhere('1=1');
        $this->assertEquals('1=1', $this->getWhereStatement());
    }

    public function testMultipleClauses()
    {
        $this->where(['1=1', '2=2']);
        $this->assertEquals('(1=1 AND 2=2)', $this->getWhereStatement());
    }

    public function testCumulativeConditions()
    {
        $this->where('1=1')
            ->orWhere(['2=2', '3=3']);
        $this->assertEquals(
            '1=1 OR (2=2 AND 3=3)',
            $this->getWhereStatement()
        );
    }

    public function testSimplePlaceHolders()
    {
        $this->andWhere(['1 = ?' => '1']);
        $this->assertEquals('1 = ?', $this->getWhereStatement());
        $this->assertEquals(['1'], $this->getParameters());
    }

    public function testMultipleConditionsAndParameters()
    {
        $this->where(
            [
                '1 = :one' => [
                    ':one' => '1'
                ],
                '2 <> :two' => [
                    ':two' => '2'
                ]
            ]
        );
        $this->assertEquals(
            '(1 = :one AND 2 <> :two)',
            $this->getWhereStatement()
        );
        $this->assertEquals(
            [':one' => '1', ':two' => '2'],
            $this->getParameters()
        );
    }

    public function testMultipleMarkers()
    {
        $this->where(
            [
                '1 = ?' => ['1'],
                '2 <> ?' => ['2']
            ]
        );
        $this->assertEquals(
            '(1 = ? AND 2 <> ?)',
            $this->getWhereStatement()
        );
        $this->assertEquals(
            ['1', '2'],
            $this->getParameters()
        );
    }
}
