<?php
/**
 * Error: a container's child (a subcontainer or a type) is not found
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class ItemsChildNotFound extends Logic implements Items
{
    /**
     * Constructor
     *
     * @param string $key
     */
    public function __construct($key = null)
    {
        $this->key = $key;
        $message = 'Items.'.$key.' is not found';
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @var string
     */
    private $key;
}
