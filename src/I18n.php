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
 *
 * @property-read \go\I18n\Locale $current
 *                the current locale
 * @property-read \go\I18n\UI\INode $ui
 *                the user interface service
 * @property-read \go\I18n\Items\IMultiContainer
 *                the items service
 *
 */
class I18n extends Helpers\MagicFields
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
     * Set the current language
     *
     * @param string $language
     * @throws \go\I18n\Exceptions\CurrentAlreadySpecified
     */
    public function setCurrentLanguage($language)
    {
        if ($this->context->current) {
            throw new Exceptions\CurrentAlreadySpecified();
        }
        $this->context->mustLanguageExists($language);
        $this->context->current = $language;
    }

    /**
     * Get the locale for the specified language
     *
     * @param string $language
     * @return \go\I18n\Locale
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocale($language)
    {
        $locales = &$this->context->locales;
        if (!isset($locales[$language])) {
            $locales[$language] = new Locale($this->context, $language);
        }
        return $locales[$language];
    }

    /**
     * Get the current locale
     *
     * @return \go\I18n\Locale
     * @throws \go\I18n\Exceptions\CurrentNotSpecified
     */
    public function getCurrentLocale()
    {
        if (!$this->context->current) {
            throw new Exceptions\CurrentNotSpecified();
        }
        return $this->getLocale($this->context->current);
    }

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @var array
     */
    protected $magicFields = array(
        'current' => true,
        'ui' => true,
        'items' => true,
    );

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return mixed
     */
    protected function magicFieldCreate($key)
    {
        switch ($key) {
            case 'current':
                return $this->getCurrentLocale();
            case 'ui':
                return $this->context->getUI();
            case 'items':
                return $this->context->getItems();
        }
        return $this->getLocale($key);
    }

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return boolean
     */
    protected function magicFieldIsset($key)
    {
        return isset($this->context->languages[$key]);
    }

    /**
     * The shared context
     *
     * @var \go\I18n\Helpers\Context
     */
    private $context;
}
