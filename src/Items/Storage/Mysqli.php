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
        $res = $res ? \MYSQLI_USE_RESULT : \MYSQLI_STORE_RESULT;
        $result = $this->db->query($sql, $res);
        if ($this->db->errno) {
            throw new \mysqli_sql_exception($this->db->error, $this->db->errno);
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
        return '"'.$this->db->real_escape_string($value).'"';
    }
}
