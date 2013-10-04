<?php
/**
 * Error: config invalid
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class ConfigInvalid extends Logic implements Config
{
    /**
     * Constructor
     *
     * @param string $error [optional]
     */
    public function __construct($error = null)
    {
        $message = 'Config (i18n) invalid';
        if ($error) {
            $message .= '. '.$error;
        }
        parent::__construct($message);
    }
}
