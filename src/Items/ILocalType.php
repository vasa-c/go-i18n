<?php
/**
 * Interface for single-language types
 *
 * ArrayAccess - access to items by cid
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

interface ILocalType extends \ArrayAccess
{
    /**
     * Get the key of this type
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the name of this type
     *
     * @return string
     */
    public function getName();

    /**
     * Get the language of this localization
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Get the parent multi-type
     *
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMulti();

    /**
     * Get the local item of this type
     *
     * @param string $cid
     * @param array|true $fields [optional]
     *        fields for preload (true - all)
     * @return \go\I18n\Items\ILocalItem
     */
    public function getItem($cid, $fields = null);

    /**
     * Get the items list of this type
     *
     * @param array $cid
     *        the list of cids
     * @param array|true $fields [optional]
     *        fields for preload (true - all)
     * @return array
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function getListItems(array $cid, $fields = null);

    /**
     * Fill data array
     *
     * @param array $a
     *        the original array
     * @param array|string $fields
     *        a field name or a list of fields
     * @param string $cidrow [optional]
     *        a field from the original array for join ("id" by default)
     * @return array
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function fillArray(array $a, $fields, $cidrow = null);

    /**
     * Remove the item by CID
     *
     * @param string|int $cid
     */
    public function removeItem($cid);
}
