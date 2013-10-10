<?php
/**
 * The basic implementation of IMultiType interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class MultiType implements IMultiType
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     *        the system context
     * @param string $key
     *        the key of this type
     * @param array $config
     *        the configuration of this container
     */
    public function __construct(\go\I18n\Helpers\Context $context, $key, array $config)
    {
        $this->context = $context;
        $this->key = $key;
        $this->pkey = $key ? $key.'.' : '';
        $this->config = $config;
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return string
     */
    public function getName()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language)
    {
        if (!isset($this->locales[$language])) {
            $this->context->mustLanguageExists($language);
            $this->locales[$language] = $this->createLocal($language);
        }
        return $this->locales[$language];
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param int|string $cid
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMultiItem($cid)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return \go\I18n\Items\Storage\IStorage
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public function getStorage()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     */
    public function removeAll()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __get($key)
    {

    }

    /**
     * Magic isset (local type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {

    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {

    }

    /**
     * @param string $language
     * @return \go\I18n\Items\ILocalType
     */
    protected function createLocal($language)
    {
        return new LocalType($this->context, $this, $language);
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $pkey;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $locales = array();
}
