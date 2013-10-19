<?php
/**
 * The declenstion node for UI
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

class UINode implements \go\I18n\UI\IAmArray, \ArrayAccess
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param array $value
     */
    public function __construct(\go\I18n\Helpers\Context $context, array $value)
    {
        $this->context = $context;
        unset($value['__type']);
        $this->value = $value;
    }

    /**
     * @override \go\I18n\UI\IAmArray
     *
     * @return array
     */
    public function asArray()
    {
        return $this->value;
    }

    /**
     * Decline in the number
     *
     * @param int $number
     */
    public function decline($number)
    {
        return $this->context->getDeclension()->decline($number, $this->value);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->value[$offset]) ? $this->value[$offset] : null;
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
        throw new \go\I18n\Exceptions\ReadOnly('Decline UI node');
    }

    /**
     * @override \ArrayAccess
     *
     * @param string $offset
     * @throws \go\I18n\Exceptions\ReadOnly
     */
    public function offsetUnset($offset)
    {
        throw new \go\I18n\Exceptions\ReadOnly('Decline UI node');
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    private $context;

    /**
     * @var array
     */
    private $value;
}
