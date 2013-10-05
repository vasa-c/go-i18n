<?php
/**
 * A field is not found
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

final class FieldNotFound extends Logic
{
    /**
     * Constructor
     *
     * @param string $container [optional]
     * @param string $key [optional]
     */
    public function __construct($container = null, $key = null)
    {
        $message = '"'.$key.'" is not found in "'.$container.'"';
        parent::__construct($message);
    }
}
