<?php
/**
 * Invalid configuration of the service
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class ConfigService extends Logic implements Config
{
    /**
     * Construct
     *
     * @param string $service [optional]
     * @param string $error [optional]
     */
    public function __construct($service = null, $error = null)
    {
        $message = 'Config service "'.$service.'" is invalid';
        if ($error) {
            $message .= '. '.$error;
        }
        parent::__construct($message);
    }
}
