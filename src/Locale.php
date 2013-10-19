<?php
/**
 * The localization class
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n;

use go\I18n\Exceptions\LocaleEmptyMode;

/**
 * @property-read string $language
 *                the language for this locale
 * @property-read array $paramsLanguage
 *                the parameters list of language
 * @property-read \go\I18n\I18n $i18n
 *                the main i18n object
 * @property-read \go\I18n\Locale $parent
 *                the parent locale or NULL if it is not exists
 * @property-read \go\I18n\UI\INode $ui
 *                the user interface service for this locale
 * @property-read \go\I18n\Items\ILocalContainer $items
 *                the items local container
 */
class Locale extends Helpers\MagicFields
{
    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @var array
     */
    protected $magicFields = array(
        'language' => true,
        'paramsLanguage' => true,
        'i18n' => true,
        'parent' => true,
        'ui' => true,
        'items' => true,
    );

    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     *        the context of i18n-system
     * @param string $language
     *        the language for this locale
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __construct(Helpers\Context $context, $language)
    {
        if ($language !== null) {
            $context->mustLanguageExists($language);
        }
        $this->context = $context;
        $this->language = $language;
    }

    /**
     * Check if the locale language is current for i18n
     *
     * @return boolean
     */
    public function isCurrent()
    {
        $this->checkEmpty(false);
        return ($this->language === $this->context->current);
    }

    /**
     * Check if the locale language is default for i18n
     *
     * @return boolean
     */
    public function isDefault()
    {
        $this->checkEmpty(true);
        return ($this->language === $this->context->default);
    }

    /**
     * Check if the locale is empty (empty mode)
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return ($this->language === null);
    }

    /**
     * Create the url of resource (for this locale)
     *
     * @param string $relUrl
     *        a relative url (in the current language version)
     * @param array|string $data [optional]
     *        data for GET
     * @param boolean $absolute [optional]
     *        create an absolute URI
     * @throws \go\I18n\Exceptions\UrlsNotResolverd
     */
    public function url($relUrl, $data = null, $absolute = false)
    {
        if (!$this->urls) {
            $this->urls = $this->context->getUrls();
        }
        return $this->urls->url($relUrl, $data, $absolute, $this->language);
    }

    /**
     * Decline in the number
     *
     * @param int $number
     *        the number of objects
     * @param string|array $forms
     *        array - the list of declension forms, string - UI-key
     * @return string
     *         required form
     */
    public function decline($number, array $forms)
    {
        return $this->context->getDeclension()->decline($number, $forms, $this->language);
    }

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return mixed
     */
    protected function magicFieldCreate($key)
    {
        if ($key === 'i18n') {
            return $this->context->i18n;
        }
        $this->checkEmpty(true);
        switch ($key) {
            case 'language':
                return $this->language;
            case 'paramsLanguage':
                return $this->context->languages[$this->language];
            case 'parent':
                $parent = $this->context->languages[$this->language]['parent'];
                return $parent ? $this->context->i18n->getLocale($parent) : null;
            case 'ui':
                return $this->context->i18n->ui->__get($this->language);
            case 'items':
                return $this->context->getItems()->getLocal($this->language);
        }
    }

    /**
     * @param boolean $throw [optional]
     * @throws \go\I18n\Exceptions\LocaleEmptyMode
     */
    protected function checkEmpty($throw = false)
    {
        if ($this->language === null) {
            if ($this->context->current) {
                $this->language = $this->context->current;
            } elseif ($throw) {
                throw new Exceptions\LocaleEmptyMode();
            }
        }
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    private $context;

    /**
     * @var string
     */
    private $language;

    /**
     * @var \go\I18n\Urls\IUrls
     */
    private $urls;
}
