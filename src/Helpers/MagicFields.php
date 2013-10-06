<?php
/**
 * Basic class for a service with subservices (access by magic fields)
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\FieldNotFound;
use go\I18n\Exceptions\ReadOnly;

abstract class MagicFields
{
    /**
     * List of fields for magic access (for override)
     *
     * @var array
     *      field name => true
     */
    protected $magicFields = array();

    /**
     * Name of this container (for override, class name by default)
     *
     * @var string
     */
    protected $magicContainer = null;

    /**
     * Magic get
     *
     * @param string $key
     * @return mixed
     * @throws \go\I18n\Exceptions\FieldNotFound
     */
    public function __get($key)
    {
        if (!\array_key_exists($key, $this->magicFieldsValues)) {
            if (!$this->__isset($key)) {
                throw new FieldNotFound($this->getMagicContainer(), $key);
            }
            $this->magicFieldsValues[$key] = $this->magicFieldCreate($key);
        }
        return $this->magicFieldsValues[$key];
    }

    /**
     * Magic isset
     *
     * @param string $key
     * @return boolean
     * @throws \go\I18n\Exceptions\FieldNotFound
     */
    public function __isset($key)
    {
        if (!isset($this->magicFields[$key])) {
            if (!$this->magicFieldIsset($key)) {
                return false;
            }
            $this->magicFields[$key] = true;
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
        throw new \go\I18n\Exceptions\ReadOnly($this->getMagicContainer());
    }

    /**
     * Magic unset (forbidden)
     *
     * @param string $key
     * @param mixed $value
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function __unset($key)
    {
        throw new \go\I18n\Exceptions\ReadOnly($this->getMagicContainer());
    }

    /**
     * Create the field value
     *
     * @param string $key
     * @return mixed
     */
    protected function magicFieldCreate($key)
    {
    }

    /**
     * Check if field exists (for dynamic fields)
     *
     * @param string $key
     * @return boolean
     */
    protected function magicFieldIsset($key)
    {
        return false;
    }

    /**
     * @return string
     */
    private function getMagicContainer()
    {
        if (!$this->magicContainer) {
            $cn = \get_class($this);
            $cn = \explode('\\', $cn);
            $this->magicContainer = \array_pop($cn);
        }
        return $this->magicContainer;
    }

    /**
     * The cache of fields values
     *
     * @var array
     */
    private $magicFieldsValues = array();
}
