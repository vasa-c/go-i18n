<?php
/**
 * The test storage
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmial.com>
 */

namespace go\Tests\I18n\Items\mocks;

class TStorage implements \go\I18n\Items\Storage\IStorage
{
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param string|int $cid
     * @return array
     */
    public function getFieldsForItem(array $fields, $type, $language, $cid)
    {

    }

    /**
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param array $cids
     * @return array
     */
    public function getFieldsForList(array $fields, $type, $language, array $cids)
    {

    }

    /**
     * @param string $type
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeItem($type, $cid)
    {

    }

    /**
     * @param string $type
     * @param string $language
     * @param string|int $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeLocalItem($type, $language, $cid)
    {

    }

    /**
     * @param array $fields
     * @param string $type
     * @param string $language
     * @param int|string $cid
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeFields(array $fields, $type, $language, $cid)
    {

    }

    /**
     * @param string $type
     * @throws \go\I18n\Exceptions\StorageReadOnly
     */
    public function removeType($type)
    {

    }

    /**
     * @var array
     */
    private $params;
}
