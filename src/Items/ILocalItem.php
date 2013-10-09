<?php
/**
 * Interface for single-language items
 *
 * ArrayAccess - alias for magic methods
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface ILocalItem extends \ArrayAccess
{
    /**
     * Get the parent multi item
     *
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMulti();

    /**
     * Get the item instance for other locale
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getAnotherLanguage($language);

    /**
     * Get the instance of local type of this item
     *
     * @return \go\I18n\Items\ILocalType
     */
    public function getType();

    /**
     * Get CID of this item
     *
     * @return int|string
     */
    public function getCID();

    /**
     * Get the list of specified fields
     *
     * @param array $fields
     * @return array
     */
    public function getListFields($fields);

    /**
     * Get the list of only fields that loaded
     *
     * @return array
     */
    public function getLoadedFields();

    /**
     * Set values of fields
     *
     * @param array $fields
     *        the fields list (name => new value)
     * @param boolean $save [optional]
     *        save after set
     */
    public function setListFields($fields, $save = true);

    /**
     * Save new values
     */
    public function save();

    /**
     * Remove this item (all locales)
     */
    public function remove();

    /**
     * Clear all fields for this locale only
     */
    public function clear();

    /**
     * Magic get (value of field)
     *
     * @param string $key
     * @return string
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __get($key);

    /**
     * Magic isset (value of field)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key);

    /**
     * Magic set (value of field)
     *
     * @param string $key
     * @param string $value
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __set($key, $value);

    /**
     * Magic unset (clear field)
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function __unset($key, $value);

    /**
     * Set known values
     *
     * @param array $fields
     *        name => value
     */
    public function knownValuesSet($fields);
}
