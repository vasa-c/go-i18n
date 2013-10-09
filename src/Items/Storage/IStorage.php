<?php
/**
 * The interface of an items storage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items\Storage;

interface IStorage
{
    /**
     * Get the fields list for the specified item
     *
     * @param array $fields
     *        the list of fields names
     * @param string $type
     *        the type of item
     * @param string $language
     *        the locale
     * @param string|int $cid
     *        the CID of item
     * @return array
     *         the list of fields (only found)
     */
    public function getFieldsForItem(array $fields, $type, $language, $cid);

    /**
     * Get the fields list for the items list
     *
     * @param array $fields
     *        the list of fields names
     * @param string $type
     *        the type of item
     * @param string $language
     *        the locale
     * @param array $cids
     *        the list of items CIDs
     * @return array
     *         the list of items fields (key persists, only founded)
     */
    public function getFieldsForList(array $fields, $type, $language, array $cids);

    /**
     * Remove the specified item
     *
     * @param string $type
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeItem($type, $cid);

    /**
     * Remove the specified item localization
     *
     * @param string $type
     * @param string $language
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeLocalItem($type, $language, $cid);

    /**
     * Remove specified fields of the item
     *
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param int|string $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeFields(array $fields, $type, $language, $cid);

    /**
     * Remove all items of specified type
     *
     * @param string $type
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeType($type);
}
