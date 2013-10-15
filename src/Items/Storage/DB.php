<?php
/**
 * The basic class for databases implementations of IStorage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

use go\I18n\Exceptions\ConfigInvalid;
use go\I18n\Exceptions\StorageReadOnly;

abstract class DB implements IStorage
{
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
        if (!isset($params['table'])) {
            throw new ConfigInvalid('Table is not specified for DB-Storage');
        }
        $this->table = $params['table'];
        $this->readonly = !empty($params['readonly']);
        $this->biglen = isset($params['biglen']) ? (int)$params['biglen'] : 255;
        if (\function_exists('mb_strlen')) {
            $this->charset = isset($params['charset']) ? $params['charset'] : 'utf-8';
        }
        $this->loadCols();
        $this->init();
    }

    /**
     * Init storage (for override)
     *
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    protected function init()
    {
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param string|int $cid
     * @return array
     */
    public function getFieldsForItem(array $fields, $type, $language, $cid)
    {
        if (empty($fields)) {
            return array();
        }
        $ctype = $this->cols['type'];
        $ccid = \is_int($cid) ? $this->cols['cid'] : $this->cols['cid_key'];
        $clang = $this->cols['language'];
        $cfield = $this->cols['field'];
        $cvalue = $this->cols['value'];
        $cvalueb = $this->cols['value_big'];
        $cols = array($cfield, $cvalue);
        if ($cvalueb) {
            $cols[] = $cvalueb;
        }
        $where = array();
        if ($ctype) {
            $where[$ctype] = $type;
        }
        $where[$clang] = $language;
        $where[$ccid] = $cid;
        if (\count($fields) === 1) {
            $fields = $fields[0];
        }
        $where[$cfield] = $fields;
        $result = array();
        foreach ($this->select($cols, $where) as $row) {
            $value = $row[$cvalue];
            if ($value === null) {
                if ($cvalueb) {
                    $value = $row[$cvalueb];
                }
            }
            $result[$row[$cfield]] = $value;
        }
        return $result;
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param array $cids
     * @return array
     */
    public function getFieldsForList(array $fields, $type, $language, array $cids)
    {
        if (empty($fields)) {
            return array();
        }
        if (empty($cids)) {
            return array();
        }
        \reset($cids);
        $ctype = $this->cols['type'];
        $ccid = \is_int(\current($cids)) ? $this->cols['cid'] : $this->cols['cid_key'];
        $clang = $this->cols['language'];
        $cfield = $this->cols['field'];
        $cvalue = $this->cols['value'];
        $cvalueb = $this->cols['value_big'];
        $cols = array($ccid, $cfield, $cvalue);
        if ($cvalueb) {
            $cols[] = $cvalueb;
        }
        $where = array();
        if ($ctype) {
            $where[$ctype] = $type;
        }
        $where[$clang] = $language;
        $where[$ccid] = $cids;

        if (\count($fields) === 1) {
            $fields = $fields[0];
        }
        $where[$cfield] = $fields;
        $result = array();
        foreach ($cids as $cid) {
            $result[$cid] = array();
        }
        foreach ($this->select($cols, $where) as $row) {
            $value = $row[$cvalue];
            if ($value === null) {
                if ($cvalueb) {
                    $value = $row[$cvalueb];
                }
            }
            $result[$row[$ccid]][$row[$cfield]] = $value;
        }
        return $result;
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param string $type
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeItem($type, $cid)
    {
        if ($this->readonly) {
            throw new StorageReadOnly();
        }
        $ctype = $this->cols['type'];
        $ccid = \is_int($cid) ? $this->cols['cid'] : $this->cols['cid_key'];
        $where = array();
        if ($ctype) {
            $where[$ctype] = $type;
        }
        $where[$ccid] = $cid;
        $this->delete($where);
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param string $type
     * @param string $language
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeLocalItem($type, $language, $cid)
    {
        if ($this->readonly) {
            throw new StorageReadOnly();
        }
        $ctype = $this->cols['type'];
        $ccid = \is_int($cid) ? $this->cols['cid'] : $this->cols['cid_key'];
        $where = array();
        if ($ctype) {
            $where[$ctype] = $type;
        }
        $where[$this->cols['language']] = $language;
        $where[$ccid] = $cid;
        $this->delete($where);
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param int|string $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeFields(array $fields, $type, $language, $cid)
    {
        if ($this->readonly) {
            throw new StorageReadOnly();
        }
        $ctype = $this->cols['type'];
        $ccid = \is_int($cid) ? $this->cols['cid'] : $this->cols['cid_key'];
        $where = array();
        if ($ctype) {
            $where[$ctype] = $type;
        }
        $where[$this->cols['language']] = $language;
        $where[$ccid] = $cid;
        $where[$this->cols['field']] = $fields;
        $this->delete($where);
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param string $type
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeType($type)
    {
        if ($this->readonly) {
            throw new StorageReadOnly();
        }
        $ctype = $this->cols['type'];
        if ($ctype) {
            $where = array($ctype => $type);
        } else {
            $where = null;
        }
        $this->delete($where);
    }

    /**
     * @override \go\I18n\Items\Storage\IStorage
     *
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param int|string $cid
     */
    public function setFields(array $fields, $type, $language, $cid)
    {
        if ($this->readonly) {
            throw new StorageReadOnly();
        }
        $listValues = array();
        $ctype = $this->cols['type'];
        $clang = $this->cols['language'];
        $ccid = \is_int($cid) ? $this->cols['cid'] : $this->cols['cid_key'];
        $cfield = $this->cols['field'];
        $cvalue = $this->cols['value'];
        $cvalueb =  $this->cols['value_big'];
        $dvalues = array();
        if ($ctype) {
            $dvalues[$ctype] = $type;
        }
        $dvalues[$clang] = $language;
        $dvalues[$ccid] = $cid;
        foreach ($fields as $key => $value) {
            $values = $dvalues;
            $values[$cfield] = $key;
            if ($cvalueb) {
                $len = $this->charset ? \mb_strlen($value, $this->charset) : \strlen($value);
                if ($len > $this->biglen) {
                    $values[$cvalue] = null;
                    $values[$cvalueb] = $value;
                } else {
                    $values[$cvalue] = $value;
                    $values[$cvalueb] = null;
                }
            } else {
                $values[$cvalue] = $value;
            }
            $listValues[] = $values;
        }
        $this->replaceMulti($listValues);
    }

    /**
     * Select rows from the database
     *
     * @param array $cols
     * @param array $where [optional]
     * @return array
     */
    abstract protected function select(array $cols, array $where = null);

    /**
     * Replace a row in the database
     *
     * @param array $values
     * @param array $cols [optional]
     */
    abstract protected function replace(array $values, array $cols = null);

    /**
     * Replace list of rows in the database
     *
     * @param array $listValues
     * @param array $cols [optional]
     */
    protected function replaceMulti(array $listValues, array $cols = null)
    {
        foreach ($listValues as $values) {
            if (!$cols) {
                $cols = \array_keys($values);
            }
            $this->replace($values, $cols);
        }
    }

    /**
     * Remove rows from databases by condition
     *
     * @param array $where [optional]
     */
    abstract protected function delete(array $where = null);

    /**
     * Get the database resource
     *
     * @return mixed
     * @throw \go\I18n\Exceptions\ConfigInvalid
     */
    protected function getDB()
    {
        if ($this->db === null) {
            if (isset($this->params['db'])) {
                $this->db = $this->params['db'];
            } elseif (isset($this->params['db_params'])) {
                $this->db = $this->createDB($this->params['db_params']);
            } else {
                throw new ConfigInvalid('Parameters "db" or "db_params" is not found in storage config');
            }
        }
        return $this->db;
    }

    /**
     * Create the db resource (for override)
     *
     * @param mixed $params
     * @return mixed
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    protected function createDB($params)
    {
        throw new \LogicException('Storage::createDB is not implemented');
    }

    /**
     * Load and normalize the columns list
     */
    private function loadCols()
    {
        $this->cols = $this->defaultCols;
        if (!isset($this->params['cols'])) {
            return;
        }
        $this->cols = \array_merge($this->cols, $this->params['cols']);
        if (empty($this->cols['cid_key'])) {
            $this->cols['cid_key'] = $this->cols['cid'];
        }
        if (empty($this->cols['value_big'])) {
            $this->biglen = null;
        }
    }

    /**
     * @var array
     */
    private $defaultCols = array(
        'type' => 'type',
        'language' => 'language',
        'cid' => 'cid',
        'cid_key' => 'cid_key',
        'field' => 'field',
        'value' => 'value',
        'value_big' => 'value_big',
    );

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $cols;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var boolean
     */
    protected $readonly;

    /**
     * @var string
     */
    protected $biglen;

    /**
     * @var string
     */
    protected $charset;

    /**
     * @var mixed
     */
    protected $db;
}
