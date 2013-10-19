<?php
/**
 * The basic implementation of the IDeclension interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

abstract class Base implements IDeclension
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param array $params
     */
    public function __construct(\go\I18n\Helpers\Context $context, array $params)
    {
        $this->context = $context;
        $this->params = $params;
        $this->init();
    }

    /**
     * @override \go\I18n\Declension\IDeclension
     *
     * @param int $number
     * @param string|array $forms
     * @param string $language [optional]
     * @return string
     */
    public function decline($number, $forms, $language = null)
    {
        if (!$language) {
            $language = $this->context->current ?: $this->context->default;
        }
        if (!\is_array($forms)) {
            $forms = $this->context->getUI()->get($language.'.'.$forms);
            if (\is_object($forms)) {
                if ($forms instanceof \go\I18n\UI\IAmArray) {
                    $forms = $forms->asArray();
                } else {
                    $forms = array();
                }
            } elseif (!\is_array($forms)) {
                $forms = array($forms);
            }
        }
        $locale = $this->getLocale($language);
        if ($locale) {
            return \call_user_func($locale, $number, $forms);
        }
        switch (\count($forms)) {
            case 0:
                return '';
            case 1:
                return isset($forms[0]) ? $forms[0] : '';
            case 2:
                return $this->default2($number, $forms);
            default:
                return $this->default3($number, $forms);
        }
    }

    /**
     * Init (for override)
     */
    protected function init()
    {
    }

    /**
     * Get a locale implementation
     *
     * @param string $language
     * @return callable|NULL
     */
    protected function getLocale($language)
    {
        if (!\array_key_exists($language, $this->locales)) {
            $locale = $this->loadLocale($language);
            if (!$locale) {
                $parent = $this->context->languages[$language]['parent'];
                if ($parent) {
                    $locale = $this->getLocale($parent);
                }
            }
            $this->locales[$language] = $locale;
        }
        return $this->locales[$language];
    }

    /**
     * Load an implementation for this language
     *
     * @param string $language
     * @return callable|NULL
     */
    abstract protected function loadLocale($language);

    /**
     * @param int $number
     * @param array $forms
     * @return string
     */
    private function default2($number, $forms)
    {
        if (\abs($number) === 1) {
            return isset($forms[0]) ? $forms[0] : '';
        }
        return isset($forms[1]) ? $forms[1] : '';
    }

    /**
     * @param int $number
     * @param array $forms
     * @return string
     */
    private function default3($number, $forms)
    {
        $number = \abs($number) % 100;
        if (($number >= 5) && ($number <= 20)) {
            return isset($forms[2]) ? $forms[2] : '';
        }
        $number %= 10;
        if ($number == 1) {
            return isset($forms[0]) ? $forms[0] : '';
        }
        if (($number >= 2) && ($number <= 4)) {
            return isset($forms[1]) ? $forms[1] : '';
        }
        return isset($forms[2]) ? $forms[2] : '';
    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $locales = array();
}
