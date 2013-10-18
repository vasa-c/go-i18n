<?php
/**
 * Urls is not resolved
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class UrlsNotResolved extends Logic implements Exception
{
    /**
     * @override \go\I18n\Exceptions\Logic
     *
     * @var string
     */
    protected $errorMessage = 'I18n->urls is not resolved';
}
