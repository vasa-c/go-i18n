<?php
/**
 * Basic class for db-adapters with direct queries
 * (without prepare statement, placehoders, ORM  and etc)
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

abstract class DBPlainQueries extends DB
{
    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    protected function init()
    {
        if (!isset($this->params['db'])) {
            throw new ConfigInvalid('Storage required the field "db"');
        }
        $this->db = $this->params['db'];
        if (!empty($this->params['logger'])) {
            $this->logger = $this->params['logger'];
        }
    }

    /**
     * Implementation query for this db type
     *
     * @param string $sql
     *        query string
     * @param boolean $res [optional]
     *        required to get the result
     * @return object
     *         result object (implementation dependent)
     */
    abstract protected function realQuery($sql, $res = false);

    /**
     * Fetch the result of query to an associative array
     *
     * @param object $result
     * @return array
     */
    protected function fetchResult($result)
    {
        return \iterator_to_array($result);
    }

    /**
     * @param string $col
     * @return string
     */
    protected function escapeCol($col)
    {
        return $col;
    }

    /**
     * @param string $table
     * @return string
     */
    protected function escapeTable($table)
    {
        return $this->escapeCol($table);
    }

    /**
     * @param string $value
     * @return string
     */
    protected function escapeValue($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return '"'.\addslashes($value).'"';
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $cols
     * @param array $where [optional]
     * @return array
     */
    protected function select(array $cols, array $where = null)
    {
        $cols = $this->createColsList($cols);
        $where = $this->createWhereStatement($where);
        $table = $this->escapeTable($this->table);
        $sql = 'SELECT '.$cols.' FROM '.$table.' WHERE '.$where;
        $result = $this->query($sql, true);
        return $this->fetchResult($result);
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $values
     * @param array $cols [optional]
     */
    protected function replace(array $values, array $cols = null)
    {
        if (!$cols) {
            $cols = \array_keys($values);
        }
        $cols = $this->createColsList($cols);
        $values = $this->createValuesList($values);
        $table = $this->escapeTable($this->table);
        $sql = 'REPLACE INTO '.$table.' ('.$cols.') VALUES ('.$values.')';
        $this->query($sql);
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $listValues
     * @param array $cols [optional]
     */
    protected function replaceMulti(array $listValues, array $cols = null)
    {
        if (empty($listValues)) {
            return;
        }
        $rvalues = array();
        foreach ($listValues as $values) {
            if (!$cols) {
                $cols = $this->createColsList(\array_keys($values));
            }
            $rvalues[] = '('.$this->createValuesList($values).')';
        }
        $table = $this->escapeTable($this->table);
        $sql = 'REPLACE INTO '.$table.' ('.$cols.') VALUES '.\implode(',', $rvalues);
        $this->query($sql);
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $where [optional]
     */
    protected function delete(array $where = null)
    {
        $where = $this->createWhereStatement($where);
        $table = $this->escapeTable($this->table);
        $sql = 'DELETE FROM '.$table.' WHERE '.$where;
        $this->query($sql);
    }

    /**
     * @param array $cols
     * @return string
     */
    protected function createColsList(array $cols)
    {
        $cols = \array_map(array($this, 'escapeCol'), $cols);
        return \implode(',', $cols);
    }

    /**
     * @param array $values
     * @return string
     */
    protected function createValuesList(array $values)
    {
        $values = \array_map(array($this, 'escapeValue'), $values);
        return \implode(',', $values);
    }

    /**
     * @param array $where
     * @return string
     */
    protected function createWhereStatement(array $where = null)
    {
        if (empty($where)) {
            return '1';
        }
        $result = array();
        foreach ($where as $col => $value) {
            $col = $this->escapeCol($col);
            if (\is_array($value)) {
                if (!empty($value)) {
                    $result[] = $col.' IN ('.$this->createValuesList($value).')';
                }
            } else {
                $result[] = $col.'='.$this->escapeValue($value);
            }
        }
        return \implode(' AND ', $result);
    }

    /**
     * @param string $sql
     * @param boolean $res [optional]
     * @return object
     */
    protected function query($sql, $res = false)
    {
        if ($this->logger) {
            \call_user_func($this->logger, $sql);
        }
        return $this->realQuery($sql, $res);
    }

    /**
     * @var object
     */
    protected $db;

    /**
     * @var callable
     */
    protected $logger;
}
