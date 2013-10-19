<?php
/**
 * The interface of a declension service
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

interface IDeclension
{
    /**
     * Decline in the number
     *
     * @param int $number
     *        the number of objects
     * @param string|array $forms
     *        array - the list of declension forms, string - UI-key
     * @param string $language [optional]
     *        the language (current or default by default)
     * @return string
     *         required form
     */
    public function decline($number, $forms, $language = null);
}
