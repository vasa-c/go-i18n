<?php
/**
 * Mysqli adapter for items storage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

class Mysqli extends DBPlainQueries
{
    /**
     * @override \go\I18n\Items\Storage\DBPlainQueries
     *
     * @param string $sql
     * @param boolean $res [optional]
     * @return object
     */
    protected function realQuery($sql, $res = false)
    {
        $db = $this->getDB();
        $res = $res ? \MYSQLI_USE_RESULT : \MYSQLI_STORE_RESULT;
        $result = $db->query($sql, $res);
        if ($db->errno) {
            throw new \mysqli_sql_exception($db->error, $db->errno);
        }
        return $result;
    }

    /**
     * @override \go\I18n\Items\Storage\DBPlainQueries
     *
     * @param string $col
     * @return string
     */
    protected function escapeCol($col)
    {
        return '`'.$col.'`';
    }

    /**
     * @override \go\I18n\Items\Storage\DBPlainQueries
     *
     * @param string $value
     * @return string
     */
    protected function escapeValue($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return '"'.$this->getDB()->real_escape_string($value).'"';
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param mixed $params
     * @return mixed
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    protected function createDB($params)
    {
        $constr = array('host', 'username', 'passwd', 'dbname', 'port', 'socket');
        $args = array();
        foreach ($constr as $c) {
            $args[] = isset($params[$c]) ? $params[$c] : null;
        }
        $class = new \ReflectionClass('mysqli');
        return $class->newInstanceArgs($args);
    }
}
