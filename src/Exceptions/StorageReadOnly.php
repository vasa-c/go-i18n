<?php
/**
 * Error: the storage is readonly
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class StorageReadOnly extends Logic implements Storage
{
    protected $errorMessage = 'I18n storage is read-only';
}
