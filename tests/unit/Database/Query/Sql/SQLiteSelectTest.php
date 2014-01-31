<?php
namespace Database\Query\Sql;
use Codeception\Util\Stub,
    Slick\Database\Database,
    Slick\Database\Query\Sql\Select;

class SQLiteSelectTest extends \Codeception\TestCase\Test
{
    protected static $_lastQuery;
    protected static $_usedParams = array();

    
    /**
     * Creates the databse connector
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(
            array(
                'type' => 'sqlite',
            )
        );
        $this->_connector = $db->initialize();
        $this->_select = $this->_connector->query()->select('users');
        unset($db);
    }

    /**
     * Settin a select query an check all return
     * @test
     * @expectedException \Slick\Database\Exception\UnsupportedSyntaxException
     */
    public function gelAllQuery()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'SQLite',
                    'connector' => $this->_select->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    return true;
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_select->setQuery($query);
        $this->_select
            ->where(array("id = '?'" => 23))
            ->andWhere(array("Active = :active" => array(':active' => true)))
            ->orderBy('name DESC')
            ->limit(15)
            ->groupBy('Origin')
            ->all();
        $expected = <<<EOS
SELECT * FROM users
WHERE id = '?' AND Active = :active
GROUP BY Origin
ORDER BY name DESC
LIMIT 15
EOS;
        $this->assertEquals(trim($expected), self::$_lastQuery);

        $this->_select
            ->limit(10, 1)
            ->join('profile', 'profile.user_id = users.id', array('picture', 'path'))
            ->all();
        $expected = <<<EOS
SELECT users.*, profile.picture, profile.path FROM users
LEFT JOIN profile ON profile.user_id = users.id
WHERE id = '?' AND Active = :active
GROUP BY Origin
ORDER BY name DESC
LIMIT 10, 1
EOS;
        $this->assertEquals(trim($expected), self::$_lastQuery);

        $this->_select
            ->join('test', 'test.id = user.id', array(), Select::JOIN_RIGHT_OUTER)
            ->all();
    }

}