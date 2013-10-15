<?php
/**
 * go\DB adapter for storage
 *
 * @link https://github.com/vasa-c/go-db
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

class GoDB extends DB
{
    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    protected function init()
    {
        parent::init();
        $this->replaceSingle = !empty($this->params['replace_single']);
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
        $pattern = 'SELECT ?cols FROM ?table WHERE';
        $data = array($cols, $this->table);
        $this->createWhere($where, $pattern, $data);
        return $this->getDB()->query($pattern, $data)->assoc();
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $values
     * @param array $cols [optional]
     */
    protected function replace(array $values, array $cols = null)
    {
        if (empty($values)) {
            return;
        }
        if (!$cols) {
            $cols = \array_keys($values);
        }
        $pattern = 'REPLACE INTO ?table (?cols) VALUES (?ln)';
        $data = array($this->table, $cols, $values);
        $this->getDB()->query($pattern, $data);
    }

    /**
     * Replace list of rows in the database
     *
     * @param array $listValues
     * @param array $cols [optional]
     */
    protected function replaceMulti(array $listValues, array $cols = null)
    {
        if ($this->replaceSingle) {
            parent::replaceMulti($listValues, $cols);
            return;
        }
        if (empty($listValues)) {
            return;
        }
        if (!$cols) {
            \reset($listValues);
            $values = \current($listValues);
            $cols = \array_keys($values);
        }
        $pattern = 'REPLACE INTO ?table (?cols) VALUES ?v';
        $data = array($this->table, $cols, $listValues);
        $this->getDB()->query($pattern, $data);
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $where [optional]
     */
    protected function delete(array $where = null)
    {
        $pattern = 'DELETE FROM ?table WHERE';
        $data = array($this->table);
        $this->createWhere($where, $pattern, $data);
        $this->getDB()->query($pattern, $data);
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
        return \go\DB\DB::create($params);
    }

    /**
     * @param mixed $where
     * @param string $pattern
     * @param array $data
     */
    protected function createWhere($where, &$pattern, &$data)
    {
        if ((!\is_array($where)) || (empty($where))) {
            $pattern .= ' 1';
            return;
        }
        $pat = array();
        foreach ($where as $k => $v) {
            $data[] = $k;
            $data[] = $v;
            if (\is_array($v)) {
                $pat[] = '?c IN (?l)';
            } else {
                $pat[] = '?c=?n';
            }
        }
        $pattern .= ' '.\implode(' AND ', $pat);
    }

    /**
     * @var boolean
     */
    protected $replaceSingle = false;
}
