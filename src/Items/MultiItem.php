<?php
/**
 * The basic implementation of the IMultiItem interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class MultiItem implements IMultiItem
{
    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @return \go\I18n\Items\IMultiType
     */
    public function getMultiType()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @return string|int
     */
    public function getCID()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     */
    public function remove()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __get($key)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        
    }
}
