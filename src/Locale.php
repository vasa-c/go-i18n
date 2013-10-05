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
