<?php

/**
 * Parser test case
 *
 * @package   Test\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Definition;

use Codeception\Util\Stub;
use Slick\Database\RecordList,
    Slick\Database\Definition\Parser;

/**
 * Parser test case
 *
 * @package   Test\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ParserTest extends \Codeception\TestCase\Test
{

    /**
     * Test factory method
     * @test
     */
    public function createAParser()
    {
        $recordList = new RecordList();
        $mysql = Parser::getParser('Mysql', $recordList);
        $sqlite = Parser::getParser('SQLite', $recordList);

        $this->assertInstanceOf(
            'Slick\Database\Definition\Parser\Mysql',
            $mysql
        );
        $this->assertInstanceOf(
            'Slick\Database\Definition\Parser\SQLite',
            $sqlite
        );
    }

}