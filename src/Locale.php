<?php
/**
 * The localization class
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n;

/**
 * @property-read string $language
 *                the language for this locale
 * @property-read array $paramsLanguage
 *                the parameters list of language
 * @property-read \go\I18n\I18n $i18n
 *                the main i18n object
 * @property-read \go\I18n\Locale $parent
 *                the parent locale or NULL if it is not exists
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
        $context->mustLanguageExists($language);
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
        return ($this->language === $this->context->current);
    }

    /**
     * Check if the locale language is default for i18n
     *
     * @return boolean
     */
    public function isDefault()
    {
        return ($this->language === $this->context->default);
    }

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return mixed
     */
    protected function magicFieldCreate($key)
    {
        switch ($key) {
            case 'language':
                return $this->language;
            case 'paramsLanguage':
                return $this->context->languages[$this->language];
            case 'i18n':
                return $this->context->i18n;
            case 'parent':
                $parent = $this->context->languages[$this->language]['parent'];
                return $parent ? $this->context->i18n->getLocale($parent) : null;
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
}
