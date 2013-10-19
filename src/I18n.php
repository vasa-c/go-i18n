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
 * @property-read \go\I18n\Items\IMultiContainer $items
 *                the items service
 * @property-read \go\I18n\Urls\IUrls $urls
 *                the urls service
 * @property-read \go\I18n\Declension\IDeclension $declension
 *                the declension service
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
     *        current language (NULL - enable empty mode)
     * @throws \go\I18n\Exceptions\CurrentAlreadySpecified
     */
    public function setCurrentLanguage($language)
    {
        if ($this->context->current) {
            throw new Exceptions\CurrentAlreadySpecified();
        }
        if ($language !== null) {
            $this->context->mustLanguageExists($language);
            $this->context->current = $language;
            if ($this->localeEmpty) {
                $this->context->locales[$language] = $this->localeEmpty;
                $this->localeEmpty = null;
            }
        } else {
            if ($this->localeEmpty) {
                throw new Exceptions\LocaleEmptyMode();
            }
            $this->localeEmpty = new Locale($this->context, null);
        }
    }

    /**
     * Get the locale for the specified language
     *
     * @param string $language
     * @return \go\I18n\Locale
     * @throws \go\I18n\Exceptions\LanguageNotExists
     * @throws \go\I18n\Exceptions\CurrentIsEmpty
     */
    public function getLocale($language)
    {
        if ($this->localeEmpty) {
            throw new Exceptions\LocaleEmptyMode();
        }
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
            if ($this->localeEmpty) {
                return $this->localeEmpty;
            }
            throw new Exceptions\CurrentNotSpecified();
        }
        return $this->getLocale($this->context->current);
    }

    /**
     * Check if i18n is in empty-locale mode
     *
     * @return boolean
     */
    public function isEmptyLocaleMode()
    {
        return !empty($this->localeEmpty);
    }

    /**
     * Set single language mode
     *
     * @throws \go\I18n\Exceptions\CurrentAlreadySpecified
     */
    public function setSingleLanguageMode()
    {
        $this->setCurrentLanguage($this->context->default);
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
        'urls' => true,
        'declension' => true,
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
            case 'urls':
                return $this->context->getUrls();
            case 'declension':
                return $this->context->getDeclension();
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

    /**
     * The empty current locale (empty mode)
     *
     * @var \go\I18n\Locale
     */
    private $localeEmpty;
}
