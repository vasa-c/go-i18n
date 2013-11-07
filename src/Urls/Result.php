<?php
/**
 * The result of urls resolve
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Urls;

/**
 * @property-read string $language
 *                the determined language version (NULL - see redirect)
 * @property-read boolean $multi
 *                use the multi languages mode (boolean)
 * @property-read string $redirect
 *                the url to redirect (NULL - not redirect)
 * @property-read string $rel_url
 *                the relative url in this language version
 * @property-read string $rel_path
 *                the relative path (the url without GET-parameters)
 * @property-read array $rel_folders
 *                the list of path folders
 */
class Result implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * Constructor
     *
     * @param array $result
     */
    public function __construct(array $result)
    {
        if ($result['rel_url'] !== null) {
            $u = \explode('?', $result['rel_url'], 2);
            $u = $u[0];
            $result['rel_path'] = $u;
            $u = \explode('/', $u);
            if (empty($u[0])) {
                \array_shift($u);
            }
            $c = \count($u);
            if ($c > 0) {
                if (empty($u[$c - 1])) {
                    \array_pop($u);
                }
            }
            $result['rel_folders'] = $u;
        } else {
            $result['rel_path'] = null;
            $result['rel_folders'] = array();
        }
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->result;
    }

    /**
     * Magic get
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (!\array_key_exists($key, $this->result)) {
            throw new \go\I18n\Exceptions\FieldNotFound('Urls result', $key);
        }
        return $this->result[$key];
    }

    /**
     * Magic isset
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return \array_key_exists($key, $this->result);
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
        throw new \go\I18n\Exceptions\ReadOnly('Urls result');
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __unset($key)
    {
        throw new \go\I18n\Exceptions\ReadOnly('Urls result');
    }

    /**
     * @override \Countable
     *
     * @return int
     */
    public function count()
    {
        return \count($this->result);
    }

    /**
     * @override \IteratorAggregate
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->result);
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
     * @throws \go\I18n\Exceptions\FieldNotFound
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
     * @var array
     */
    private $result;
}
