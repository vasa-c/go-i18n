<?php
/**
 * A key is not found in UI
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class UIKeyNotFound extends Logic implements UI
{
    /**
     * Constructor
     *
     * @param string $key [optional]
     */
    public function __construct($key = null)
    {
        $message = 'UI key'.($key ? ' "'.$key.'"' : '').' is not found';
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
