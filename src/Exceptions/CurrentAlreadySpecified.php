<?php
/**
 * Error: the current language is already specified
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class CurrentAlreadySpecified extends Logic implements Current
{
    /**
     * @override \go\I18n\Exceptions\Logic
     *
     * @var string
     */
    protected $errorMessage = 'I18n: the current language is already specified';
}
