<?php
/**
 * The error in the parsed file
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class FormatFile extends Logic implements IO
{
    /**
     * Constructor
     *
     * @param string $format [optional]
     * @param string $filename [optional]
     * @param string $error [optional]
     */
    public function __construct($format = null, $filename = null, $error = null)
    {
        $message = 'Error in '.$format.'-file: '.$error;
        if ($filename) {
            $message .= '. File: '.$filename;
        }
        parent::__construct($message);
    }
}
