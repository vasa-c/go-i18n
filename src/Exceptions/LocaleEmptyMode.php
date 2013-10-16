<?php
/**
 * Error: current locale is empty
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class LocaleEmptyMode extends Logic implements Locale
{
    /**
     * @override \go\I18n\Exceptions\Logic
     *
     * @var string
     */
    protected $errorMessage = 'I18n is in current empty mode';
}
