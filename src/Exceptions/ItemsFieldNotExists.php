<?php
/**
 * Error: the field is not exists
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class ItemsFieldNotExists extends Logic implements Items
{
    /**
     * Constructor
     *
     * @param string $field [optional]
     * @param string $type [optional]
     */
    public function __construct($field = null, $type = null)
    {
        $message = 'Field "'.$field.'" is not exists in i18n type "'.$type.'"';
        parent::__construct($message);
    }
}
