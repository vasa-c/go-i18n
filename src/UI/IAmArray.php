<?php
/**
 * Interface: the object as extension under an array
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

interface IAmArray
{
    /**
     * Represent the object as an array
     *
     * @return array
     */
    public function asArray();
}
