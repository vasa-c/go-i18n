<?php
/**
 * The basic implementation of ILocalContainer interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class LocalContainer implements ILocalContainer
{
    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getKey()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getLanguage()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return \go\I18n\Items\IMultiContainer
     */
    public function getMulti()
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getType($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

    }
}
