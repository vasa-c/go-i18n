<?php
/**
 * The test storage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmial.com>
 */

namespace go\Tests\I18n\Items\mocks;

class TStorage extends \go\I18n\Items\Storage\DB
{
    /**
     * @override \go\I18n\Items\Storage\DB
     */
    protected function init()
    {
        $this->testid = isset($this->params['testid']) ? $this->params['testid'] : null;
    }

    /**
     * @return mixed
     */
    public function getTestId()
    {
        return $this->testid;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
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
        $cols = $this->cols2string($cols);
        $where = $this->where2string($where);
        $this->queries[] = 'SELECT '.$cols.' FROM '.$this->table.' WHERE '.$where;
        return array();
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $values
     * @param array $cols [optional]
     */
    protected function replace(array $values, array $cols = null)
    {
        if (!\is_array($cols)) {
            $cols = \array_keys($values);
        }
        $cols = $this->cols2string($cols);
        $values = $this->values2string($values);
        $this->queries[] = 'REPLACE INTO '.$this->table.' ('.$cols.') VALUES ('.$values.')';
    }

    /**
     * @override \go\I18n\Items\Storage\DB
     *
     * @param array $where [optional]
     */
    protected function remove(array $where = null)
    {
        $this->queries[] = 'DELETE FROM '.$this->table.' WHERE '.$this->where2string($where);
    }

    private function cols2string($cols)
    {
        return \implode(',', $cols);
    }

    private function values2string($values)
    {
        foreach ($values as &$v) {
            if (\is_null($v)) {
                $v = 'NULL';
            }
        }
        return \implode(',', $values);
    }

    private function where2string($where)
    {
        if (empty($where)) {
            return '1';
        }
        $p = array();
        foreach ($where as $k => $v) {
            if (\is_array($v)) {
                $p[] = $k.' IN '.\implode(',', $v);
            } else {
                $p[] = $k.'='.$v;
            }
        }
        return \implode(' AND ', $p);
    }

    /**
     * @var mixed
     */
    private $testid;

    /**
     * @var array
     */
    private $queries = array();
}
