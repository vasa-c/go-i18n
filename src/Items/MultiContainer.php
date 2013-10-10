<?php
/**
 * The basic implementation of the IMultiContainer interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

use go\I18n\Helpers\Creator;
use go\I18n\Exceptions\ReadOnly;
use go\I18n\Exceptions\ItemsChildNotFound;
use go\I18n\Items\LocalContainer;

class MultiContainer implements IMultiContainer
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     *        the system context
     * @param string $key
     *        the key of this container
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
     * @override \go\I18n\Items\IMultiContainer
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalContainer
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
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiSubcontainer($path)
    {
        if (empty($path)) {
            return $this;
        }
        if (!\is_array($path)) {
            $path = \explode('.', $path);
        }
        $key = \array_shift($path);
        if (!$this->loadChildSubcontainer($key)) {
            $path = \implode('.', $path);
            $fkey = $key.($path ? '.'.$path : '');
            throw new ItemsChildNotFound($this->pkey.$fkey);
        }
        $child = $this->containers[$key];
        if (empty($path)) {
            return $child;
        }
        return $child->getMultiSubcontainer($path);
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\IMultiType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getMultiType($path)
    {
        if (empty($path)) {
            return $this;
        }
        if (!\is_array($path)) {
            $path = \explode('.', $path);
        }
        $key = \array_pop($path);
        if (empty($path)) {
            if (!$this->loadChildType($key)) {
                throw new ItemsChildNotFound($this->pkey.$key);
            }
            return $this->types[$key];
        }
        return $this->getMultiSubcontainer($path)->getMultiType($key);
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path)
    {
        try {
            $this->getMultiSubcontainer($path);
        } catch (ItemsChildNotFound $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path)
    {
        try {
            $this->getMultiType($path);
        } catch (ItemsChildNotFound $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @return \go\I18n\Items\Storage\IStorage
     */
    public function getStorage()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $key
     * @return object
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function __get($key)
    {
        if (!isset($this->magics[$key])) {
            if (isset($this->context->languages[$key])) {
                $this->magics[$key] = $this->getLocal($key);
            } elseif ($this->loadChildSubcontainer($key)) {
                $this->magics[$key] = $this->containers[$key];
            } elseif ($this->loadChildType($key)) {
                $this->magics[$key] = $this->types[$key];
            } else {
                throw new ItemsChildNotFound($this->pkey.$key);
            }
        }
        return $this->magics[$key];
    }

    /**
     * @override \go\I18n\Items\IMultiContainer
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        try {
            $this->__get($key);
        } catch (ItemsChildNotFound $e) {
            return false;
        }
        return true;
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
        throw new ReadOnly('Items container');
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __unset($key)
    {
        throw new ReadOnly('Items container');
    }

    /**
     * @param string $key
     * @return boolean
     */
    protected function loadChildSubcontainer($key)
    {
        if (isset($this->containers[$key])) {
            return true;
        }
        $config = $this->getConfigForSubcontainer($key);
        if (!$config) {
            return false;
        }
        $fkey = $this->pkey.$key;
        $options = array(
            'default' => 'go\I18n\Items\MultiContainer',
            'base' => 'go\I18n\Items\IMultiContainer',
            'key' => $fkey,
            'args' => array($this->context, $fkey),
        );
        $this->containers[$key] = Creator::create($config, $options);
        return true;
    }

    /**
     * @param string $key
     * @return boolean
     */
    protected function loadChildType($key)
    {
        if (isset($this->types[$key])) {
            return true;
        }
        $config = $this->getConfigForType($key);
        if (!$config) {
            return false;
        }
        $fkey = $this->pkey.$key;
        $options = array(
            'default' => 'go\I18n\Items\MultiType',
            'base' => 'go\I18n\Items\IMultiType',
            'key' => $fkey,
            'args' => array($this->context, $fkey),
        );
        $this->types[$key] = Creator::create($config, $options);
        return true;
    }

    /**
     * @param string $key
     * @return array|null
     */
    protected function getConfigForSubcontainer($key)
    {
        if (empty($this->config['containers'])) {
            return null;
        }
        $containers = $this->config['containers'];
        if (!isset($containers[$key])) {
            return null;
        }
        return $containers[$key];
    }

    /**
     * @param string $key
     * @return array|null
     */
    protected function getConfigForType($key)
    {
        if (empty($this->config['types'])) {
            return null;
        }
        $types = $this->config['types'];
        if (!isset($types[$key])) {
            return null;
        }
        return $types[$key];
    }

    /**
     * @param string $language
     * @return \go\I18n\Items\ILocalContainer
     */
    protected function createLocal($language)
    {
        return new LocalContainer($this->context, $this, $language);
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
    protected $containers = array();

    /**
     * @var array
     */
    protected $types = array();

    /**
     * @var array
     */
    protected $locales = array();

    /**
     * @var array
     */
    protected $magics = array();
}
