<?php
/**
 * A language is not exists
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Exceptions;

class LanguageNotExists extends Logic implements Language
{
    /**
     * Constructor
     *
     * @param string $language [optional]
     */
    public function __construct($language = null)
    {
        $this->language = $language;
        $message = 'A language "'.$language.'" is not exists';
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @var string
     */
    private $language;
}
