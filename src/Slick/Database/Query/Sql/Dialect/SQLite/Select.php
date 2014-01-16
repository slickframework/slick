<?php

/**
 * Select
 * 
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\SQLite;

use Slick\Database\Exception,
    Slick\Database\Query\Sql\Select as SqlSelect,
    Slick\Database\Query\Sql\Dialect\Standard;

/**
 * Select
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends Standard\Select
{

    /**
     * Returns the JOIN clauses for this query
     * 
     * @return string The join clauses string
     */
    public function getJoins()
    {
        $template = "%s JOIN %s ON %s";
        $joinsStr = null;
        $unsupported = array(
            SqlSelect::JOIN_NATURAL_RIGHT, SqlSelect::JOIN_NATURAL_RIGHT_OUTER,
            SqlSelect::JOIN_RIGHT_OUTER, SqlSelect::JOIN_RIGHT
        );

        $joins = $this->_sql->getJoins();
        $tmpJoin = array();
        if (count($joins) > 0) {
            foreach ($joins as $join) {

                if (in_array($join['type'], $unsupported)) {
                    throw new Exception\UnsupportedSyntaxException(
                        "'{$join['type']}' is not supported by SQLite."
                    );
                }

                $tmpJoin[] = sprintf(
                    $template,
                    $join['type'],
                    $join['table'],
                    $join['onClause']
                );
            }
            $joinsStr = implode(PHP_EOL, $tmpJoin);
        }

        return $joinsStr;
    }
}