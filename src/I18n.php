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
     *        system config
     * @param string $current [optional]
     *        current language
     */
    public function __construct(array $config, $current = null)
    {
        $this->context = new Helpers\Context($this, $config);
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
     * The shared context
     *
     * @var \go\I18n\Helpers\Context
     */
    private $context;
}
