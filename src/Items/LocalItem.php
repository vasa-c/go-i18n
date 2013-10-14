<?php
/**
 * The basic implementation of ILocalItem interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

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

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return \go\I18n\Items\ILocalType
     */
    public function getType()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return int|string
     */
    public function getCID()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param array $fields
     * @return array
     */
    public function getListFields($fields)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @return array
     */
    public function getLoadedFields()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param array $fields
     * @param boolean $save [optional]
     */
    public function setListFields($fields, $save = true)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     */
    public function save()
    {

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

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

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

    }

    /**
     * @override \go\I18n\Items\ILocalItem
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __unset($key)
    {

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

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {

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
}
