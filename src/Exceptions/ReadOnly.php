<?php
/**
 * The service is read-only
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

final class ReadOnly extends Logic
{
    /**
     * Constructor
     *
     * @param string $service [optional]
     */
    public function __construct($service = null)
    {
        if (!$service) {
            $service = 'Service';
        }
        $message = $service.' is read-only';
        parent::__construct($message);
    }
}
