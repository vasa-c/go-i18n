<?php
/**
 * Interface for multi-language type
 *
 * ArrayAccess - access to items by cid
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface IMultiType extends \ArrayAccess
{
    /**
     * Get the key of this type
     *
     * @return string
     */
    public function getKey();

    /**
     * Get name of this type
     *
     * @return string
     */
    public function getName();

    /**
     * Get the locale for this type
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language);

    /**
     * Get the instance of a multi-item
     *
     * @param int|string $cid
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMultiItem($cid);

    /**
     * Get the instance of storage
     *
     * @return \go\I18n\Items\Storage\IStorage
     * @throws \go\I18n\Exceptions\ConfigInvalid
     *         a storage is not defined in the configuration
     */
    public function getStorage();

    /**
     * Remove all items of this type
     */
    public function removeAll();

    /**
     * Magic get (local type)
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __get($key);

    /**
     * Magic isset (local type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);
}
