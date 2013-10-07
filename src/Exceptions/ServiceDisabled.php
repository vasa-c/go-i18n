<?php
/**
 * Error: the service is disabled
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class ServiceDisabled extends Logic
{
    /**
     * Construct
     *
     * @param string $service
     */
    public function __construct($service = null)
    {
        $message = 'Service'.($service ? ' "'.$service.'"' : '').' is disabled';
        parent::__construct($message);
    }
}
