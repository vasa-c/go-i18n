<?php
/**
 * The basic implementation of IMultiType interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

use go\I18n\Helpers\Creator;
use go\I18n\Exceptions\ConfigInvalid;
use go\I18n\Exceptions\ReadOnly;

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
        $this->setConfig($config);
        $this->name = isset($config['name']) ? $config['name'] : $key;
        $this->cidint = empty($config['cid_key']);
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
        return $this->name;
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
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
     * @return \go\I18n\Items\Storage\IStorage
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public function getStorage()
    {
        if (!$this->storage) {
            if (isset($this->config['storage'])) {
                $options = array(
                    'default' => null,
                    'base' => 'go\I18n\Items\Storage\IStorage',
                    'key' => 'Items.'.$this->pkey.'.Storage',
                    'ns' => 'go\I18n\Items\Storage',
                );
                $this->storage = Creator::create($this->config['storage'], $options);
            } else {
                $parent = \explode('.', $this->key);
                \array_pop($parent);
                $items = $this->context->items;
                if (!empty($parent)) {
                    $parent = $items->getMultiSubcontainer($parent);
                } else {
                    $parent = $items;
                }
                $this->storage = $parent->getStorage();
                if (!$this->storage) {
                    throw new ConfigInvalid('Storage for '.$this->key.' is not specified');
                }
            }
        }
        return $this->storage;
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param int|string $cid
     * @return \go\I18n\Items\IMultiItem
     */
    public function getMultiItem($cid)
    {
        $cid = $this->castCID($cid);
        if (!isset($this->cacheItems[$cid])) {
            $this->cacheItems[$cid] = new MultiItem($this->context, $this, $cid);
        }
        return $this->cacheItems[$cid];
    }

    /**
     * @override \go\I18n\Items\IMultiType
     */
    public function removeAll()
    {
        $this->getStorage()->removeType($this->name);
        foreach ($this->cacheItems as $item) {
            $item->resetCache();
        }
    }

    /**
     * @override \go\I18n\Items\IMultiType
     *
     * @param string|int $cid
     */
    public function removeItem($cid)
    {
        $cid = $this->castCID($cid);
        $this->getStorage()->removeItem($this->name, $cid);
        if (isset($this->cacheItems[$cid])) {
            $this->cacheItems[$cid]->resetCache();
        }
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
        return $this->getLocal($key);
    }

    /**
     * Magic isset (local type)
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->context->languages[$key]);
    }

    /**
     * Magic set (forbidden)
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __set($key, $value)
    {
        throw new ReadOnly('Items type');
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __unset($key)
    {
        throw new ReadOnly('Items type');
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
        return $this->getMultiItem($offset);
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
     * @param string $language
     * @return \go\I18n\Items\ILocalType
     */
    protected function createLocal($language)
    {
        return new LocalType($this->context, $this, $language);
    }

    /**
     * @param string|int $cid
     * @return string|int
     */
    protected function castCID($cid)
    {
        return $this->cidint ? (int)$cid : (string)$cid;
    }

    /**
     * @param array $config
     */
    private function setConfig(array $config)
    {
        if ((!isset($config['fields'])) || (!\is_array($config['fields']))) {
            throw new ConfigInvalid('Fields is not specified for i18n "'.$this->key.'"');
        }
        foreach ($config['fields'] as $k => &$v) {
            if ($v === true) {
                $v = $k;
            }
        }
        $config['rfields'] = \array_flip($config['fields']);
        $this->config = $config;
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
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $locales = array();

    /**
     * @var \go\I18n\Items\Storage\IStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected $cacheItems = array();

    /**
     * @var boolean
     */
    protected $cidint;
}
