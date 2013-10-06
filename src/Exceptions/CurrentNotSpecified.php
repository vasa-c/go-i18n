<?php
/**
 * Error: attempting to access the current language, when it is not specified
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class CurrentNotSpecified extends Logic implements Current
{
    /**
     * @override \go\I18n\Exceptions\Logic
     *
     * @var string
     */
    protected $errorMessage = 'I18n: the current language is not specified';
}
