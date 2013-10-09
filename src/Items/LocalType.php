<?php
/**
 * The basic implementation of ILocalType interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class LocalType implements ILocalType
{
    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return string
     */
    public function getKey()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return string
     */
    public function getName()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMulti()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @param string $cid
     * @param array|true $fields [optional]
     * @return \go\I18n\Items\ILocalItem
     */
    public function getItem($cid, $fields = null)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @param array $cid
     * @param array|true $fields [optional]
     * @return array
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function getListItems(array $cid, $fields = null)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @param array $a
     * @param array|string $fields
     * @param string $cidrow [optional]
     * @return array
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function fillArray(array $a, $fields, $cidrow = null)
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
