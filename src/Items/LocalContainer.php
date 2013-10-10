<?php
/**
 * The basic implementation of ILocalContainer interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

use go\I18n\Exceptions\ItemsChildNotFound;
use go\I18n\Exceptions\ReadOnly;

class LocalContainer implements ILocalContainer
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param \go\I18n\Items\IMultiContainer $multi
     * @param string $language
     */
    public function __construct(\go\I18n\Helpers\Context $context, \go\I18n\Items\IMultiContainer $multi, $language)
    {
        $this->context = $context;
        $this->multi = $multi;
        $this->language = $language;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getKey()
    {
        return $this->multi->getKey();
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return \go\I18n\Items\IMultiContainer
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getSubcontainer($path)
    {
        $ppath = \is_array($path) ? \implode('.', $path) : $path;
        if (!isset($this->containers[$ppath])) {
            $this->containers[$ppath] = $this->multi->getMultiSubcontainer($path)->getLocal($this->language);
        }
        return $this->containers[$ppath];
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getType($path)
    {
        $ppath = \is_array($path) ? \implode('.', $path) : $path;
        if (!isset($this->types[$ppath])) {
            $this->types[$ppath] = $this->multi->getMultiType($path)->getLocal($this->language);
        }
        return $this->types[$ppath];
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path)
    {
        try {
            $this->getSubcontainer($path);
        } catch (ItemsChildNotFound $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path)
    {
        try {
            $this->getType($path);
        } catch (ItemsChildNotFound $e) {
            return false;
        }
        return true;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return object
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function __get($key)
    {
        try {
            return $this->getSubcontainer($key);
        } catch (ItemsChildNotFound $e) {
        }
        return $this->getType($key);
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return ($this->existsSubcontainer($key) || $this->existsType($key));
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
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var \go\I18n\Items\IMultiContainer
     */
    protected $multi;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var array
     */
    protected $containers = array();

    /**
     * @var array
     */
    protected $types = array();
}
