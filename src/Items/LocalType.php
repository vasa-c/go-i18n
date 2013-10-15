<?php
/**
 * The basic implementation of ILocalType interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

use go\I18n\Helpers\ItemsFields;
use go\I18n\Exceptions\ReadOnly;

class LocalType implements ILocalType
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param \go\I18n\Items\IMultiContainer $multi
     * @param string $language
     */
    public function __construct(\go\I18n\Helpers\Context $context, \go\I18n\Items\IMultiType $multi, $language)
    {
        $this->context = $context;
        $this->multi = $multi;
        $this->language = $language;
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return string
     */
    public function getKey()
    {
        return $this->multi->getKey();
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return string
     */
    public function getName()
    {
        return $this->multi->getName();
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMulti()
    {
        return $this->multi;
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
        $item = $this->multi->getMultiItem($cid)->getLocal($this->language);
        if ($fields) {
            $item->knownValuesSet($fields);
        }
        return $item;
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @param array $cids
     * @param array|true $fields [optional]
     * @return array
     * @throws \go\I18n\Exceptions\ItemsFieldNotExists
     */
    public function getListItems(array $cids, $fields = null)
    {
        $result = array();
        foreach ($cids as $cid) {
            $result[$cid] = $this->getItem($cid);
        }
        if (!\is_array($fields)) {
            return $result;
        }
        $config = $this->multi->getConfig();
        $iloaded = array();
        foreach ($result as $cid => $item) {
            $iloaded[$cid] = $item->getLoadedFields();
        }
        $forload = ItemsFields::createListForLoad($iloaded, $fields, $config);
        if (empty($forload['cids'])) {
            return $result;
        }
        $storage = $this->multi->getStorage();
        $name = $this->multi->getName();
        $req = $storage->getFieldsForList($forload['fields'], $name, $this->language, $forload['cids']);
        $knowns = ItemsFields::createLoadedList($req, $forload['cids'], $fields, $config);
        foreach ($knowns as $cid => $known) {
            $result[$cid]->knownValuesSet($known);
        }
        return $result;
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
     * @override \go\I18n\Items\ILocalType
     */
    public function removeAll()
    {
        $this->multi->removeAll();
    }

    /**
     * @override \go\I18n\Items\ILocalType
     *
     * @param string|int $cid
     */
    public function removeItem($cid)
    {
        $this->multi->removeItem($cid);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return true;
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getItem($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnly('Items type');
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->removeItem($offset);
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var \go\I18n\Items\IMultiType
     */
    protected $multi;

    /**
     * @var string
     */
    protected $language;
}
