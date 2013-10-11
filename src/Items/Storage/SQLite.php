<?php
/**
 * SQLite3 adapter for items storage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

class SQLite extends DBPlainQueries
{
    protected $multiReplaceAllow = false;

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
        if ($res) {
            $result = $this->db->query($sql);
        } else {
            $result = $this->db->exec($sql);
        }
        if ($result === false) {
            throw new \RuntimeException('SQLite query error: '.$this->db->lastErrorMsg);
        }
        return $result;
    }

    /**
     * @override \go\I18n\Items\Storage\DBPlainQueries
     *
     * @param object $result
     * @return array
     */
    protected function fetchResult($result)
    {
        $arr = array();
        do {
            $row = $result->fetchArray(\SQLITE3_ASSOC);
            if ($row) {
                $arr[] = $row;
            }
        } while ($row);
        return $arr;
    }


    /**
     * @override \go\I18n\Items\Storage\DBPlainQueries
     *
     * @param string $col
     * @return string
     */
    protected function escapeCol($col)
    {
        return '"'.$col.'"';
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
        return "'".$this->db->escapeString($value)."'";
    }
}
