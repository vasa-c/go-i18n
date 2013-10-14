<?php
/**
 * Interface for multi-language items
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface IMultiItem
{
    /**
     * Get parent multi type
     *
     * @return \go\I18n\Items\IMultiType
     */
    public function getMultiType();

    /**
     * Get CID of item
     *
     * @return string|int
     */
    public function getCID();

    /**
     * Get a local version of this type
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language);

    /**
     * Remove this item
     */
    public function remove();

    /**
     * Reset the fields cache
     */
    public function resetCache();

    /**
     * Magic get (local type)
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalItem
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
