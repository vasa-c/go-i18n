<?php
/**
 * The basic implementation of the IMultiContainer interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class MultiContainer implements IMultiContainer
{
    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @return string
     */
    public function getKey()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiType($path)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @return \go\I18n\Items\Storage\IStorage
     */
    public function getStorage()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $key
     * @return object
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function __get($key)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

    }
}
