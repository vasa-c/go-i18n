<?php
/**
 * The basic implementation of ILocalItem interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

use go\I18n\Exceptions\ItemsFieldNotExists;

class LocalItem implements ILocalItem
{
    /**
     * Constructor
     *
     * @param \go\I18n\Items\MultiItem $multi
     * @param string $language
     * @param string|int $cid
     */
    public function __construct(\go\I18n\Items\MultiItem $multi, $language, $cid)
    {
        $this->multi = $multi;
        $this->language = $language;
        $this->cid = $cid;
        $mtype = $multi->getMultiType();
        $this->config = $mtype->getConfig();
        $this->typename = $mtype->getName();
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getAnotherLanguage($language)
    {
        return $this->multi->getLocal($language);
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return \go\I18n\Items\ILocalType
     */
    public function getType()
    {
        return $this->multi->getMultiType()->getLocal($this->language);
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return int|string
     */
    public function getCID()
    {
        return $this->cid;
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param array $fields [optional]
     * @return array
     */
    public function getListFields($fields = true)
    {
        $cfields = $this->config['fields'];
        if (!\is_array($fields)) {
            $fields = \array_keys($cfields);
        }
        $result = array();
        $toload = array();
        foreach ($fields as $field) {
            if (isset($this->fields[$field])) {
                $result[$field] = $this->fields[$field];
            } else {
                $toload[] = $field;
            }
        }
        if (!empty($toload)) {
            $result = \array_merge($result, $this->realLoadFields($toload, true));
        }
        return $result;
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return array
     */
    public function getLoadedFields()
    {
        return $this->fields;
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param array $fields
     * @param boolean $save [optional]
     */
    public function setListFields($fields, $save = true)
    {
        foreach ($fields as $k => $v) {
            $this->__set($k, $v);
        }
        if ($save) {
            $this->save();
        }
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     */
    public function save()
    {
        $cfields = $this->config['fields'];
        $fields = array();
        foreach ($this->tosave as $k => $v) {
            $fields[$cfields[$k]] = $v;
        }
        $this->getStorage()->setFields($fields, $this->typename, $this->language, $this->cid);
        $this->tosave = array();
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     */
    public function remove()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     */
    public function clear()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     */
    public function resetCache()
    {
        $this->fields = array();
        $this->tosave = array();
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @return string
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __get($key)
    {
        if (!isset($this->fields[$key])) {
            $this->realLoadFields(array($key), true);
        }
        return $this->fields[$key];
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->config['fields'][$key]);
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @param string $value
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __set($key, $value)
    {
        if (!isset($this->config['fields'][$key])) {
            throw new ItemsFieldNotExists($key, $this->typename);
        }
        $value = (string)$value;
        if ((isset($this->fields[$key])) && ($this->fields[$key] === $value)) {
            return;
        }
        $this->fields[$key] = $value;
        $this->tosave[$key] = $value;
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __unset($key)
    {
        $this->set($key, '');
    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param array $fields
     *        name => value
     */
    public function knownValuesSet($fields)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        return $this->__set($offset, $value);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        return $this->__unset($offset);
    }

    /**
     * @return \go\I18n\Items\Storage\IStorage
     */
    protected function getStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->multi->getMultiType()->getStorage();
        }
        return $this->storage;
    }

    /**
     * @param array $fields
     * @param boolean $tocache [optional]
     * @return array
     */
    protected function realLoadFields(array $fields, $tocache = true)
    {
        $storage = $this->getStorage();
        $cfields = $this->config['fields'];
        $rfields = $this->config['rfields'];
        $sfields = array();
        foreach ($fields as $field) {
            if (!isset($cfields[$field])) {
                throw new ItemsFieldNotExists($field, $this->typename);
            }
            $sfields[] = $cfields[$field];
        }
        $res = $storage->getFieldsForItem($sfields, $this->typename, $this->language, $this->cid);
        $result = array();
        foreach ($fields as $field) {
            $rname = $cfields[$field];
            $result[$field] = isset($res[$rname]) ? $res[$rname] : '';
        }
        if ($tocache) {
            $this->fields = \array_merge($this->fields, $result);
        }
        return $result;
    }

    /**
     * @var \go\I18n\Items\MultiItem
     */
    protected $multi;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string|int
     */
    protected $cid;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $fields = array();

    /**
     * @var \go\I18n\Items\Storage\IStorage
     */
    protected $storage;

    /**
     * @var string
     */
    protected $typename;

    /**
     * @var array
     */
    protected $tosave = array();
}
