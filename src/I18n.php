<?php
/**
 * Internationalization services
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 * @version under construction
 * @uses PHP 5.3+
 */

namespace go\I18n;

/**
 * Class of main internationalization object
 */
class I18n
{
    /**
     * Constructor
     *
     * @param array $config
     *        the system config
     * @param string $current [optional]
     *        the current language
     */
    public function __construct(array $config, $current = null)
    {
        $this->context = new Helpers\Context($this, $config);
        if ($current) {
            $this->context->current = $current;
        }
    }

    /**
     * Get a plain list of languages
     *
     * @return array
     */
    public function getListLanguages()
    {
        return \array_keys($this->context->languages);
    }

    /**
     * Get the default language
     *
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->context->default;
    }

    /**
     * Check if the language exists
     *
     * @param string $language
     * @return boolean
     */
    public function isLanguageExists($language)
    {
        return isset($this->context->languages[$language]);
    }

    /**
     * Get the current language
     *
     * @return string
     *         the current language or NULL if it is not specified
     */
    public function getCurrentLanguage()
    {
        return $this->context->current;
    }

    /**
     * The shared context
     *
     * @var \go\I18n\Helpers\Context
     */
    private $context;
}
