<?php

namespace go\I18n\UI;

use go\I18n\Exceptions\UIKeyNotFound;
use go\I18n\Exceptions\ReadOnly;

abstract class Base implements INode
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param string $key
     * @param string $language
     */
    public function __construct($context, $key, $language)
    {
        $this->context = $context;
        $this->key = $key;
        $this->pkey = ($key ? $key.'.' : '');
        $this->language = $language;
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @return \go\I18n\I18n
     */
    public function getI18n()
    {
        return $this->context->i18n;
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @param string|array $path
     * @return \go\I18n\UI\INode
     * @throws \go\I18n\Exceptions\UIKeyNotFound
     */
    public function get($path)
    {
        if (!\is_array($path)) {
            $path = \explode('.', $path);
        }
        if (!$this->exists($path)) {
            if (\is_array($path)) {
                $path = \implode($path);
            }
            throw new UIKeyNotFound($this->pkey.$path);
        }
        $current = $this;
        foreach ($path as $p) {
            $current = $current->__get($p);
        }
        return $current;
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @param string|array $path
     * @return boolean
     */
    public function exists($path)
    {
        if (!\is_array($path)) {
            $path = \explode('.', $path);
        }
        $key = \array_shift($path);
        if (!$this->__isset($key)) {
            return false;
        }
        if (empty($path)) {
            return true;
        }
        $node = $this->childs[$key];
        if ($node instanceof INode) {
            return $node->exists($path);
        }
        return false;
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @param string $key
     * @return \go\I18n\UI\INode
     * @throws \go\I18n\Exceptions\UIKeyNotFound
     */
    public function __get($key)
    {
        if (!$this->__isset($key)) {
            throw new UIKeyNotFound($this->pkey.$key);
        }
        return $this->childs[$key];
    }

    /**
     * @override \go\I18n\UI\INode
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        if (isset($this->childs[$key])) {
            return true;
        }
        if ($this->loadTry($key)) {
            return true;
        }
        if ($this->loadFromParent($key)) {
            return true;
        }
        return false;
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
        throw new ReadOnly('UI');
    }

    /**
     * Magic set (forbidden)
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __unset($key)
    {
        throw new ReadOnly('UI');
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @param mixed $value
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function offsetSet($offset, $value)
    {
        return $this->__set($offset, $value);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function offsetUnset($offset)
    {
        return $this->__unset($offset);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '[UI('.$this->language.')'.$this->key.']';
    }

    /**
     * Try load a node by the key
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        return false;
    }

    /**
     * Load a node from the parent locale
     *
     * @param string $key
     * @return \go\I18n\UI\INode|null
     */
    protected function loadFromParent($key)
    {
        if (!$this->parent) {
            if ($this->parent === false) {
                return false;
            }
            $lang = $this->context->languages[$this->language]['parent'];
            if (!$lang) {
                $this->parent = false;
                return false;
            }
            $this->parent = $this->context->ui->__get($lang);
        }
        $path = \explode('.', $this->pkey.$key);
        if (!$this->parent->exists($path)) {
            return false;
        }
        $this->childs[$key] = $this->parent->get($path);
        return true;
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
    protected $language;

    /**
     * @var array
     */
    protected $childs = array();

    /**
     * @var \go\I18n\UI\INode
     */
    protected $parent;
}
