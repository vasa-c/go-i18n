<?php
/**
 * The basic implementation of IMultiType interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class MultiType implements IMultiType
{
    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return string
     */
    public function getKey()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return string
     */
    public function getName()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param int|string $cid
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMultiItem($cid)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return \go\I18n\Items\Storage\IStorage
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public function getStorage()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     */
    public function removeAll()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __get($key)
    {

    }

    /**
     * Magic isset (local type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
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
}
