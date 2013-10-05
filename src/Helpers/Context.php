<?php
/**
 * The context is shared between all i18n-services
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\ConfigInvalid;

class Context
{
    /**
     * The initial config of i18n-object
     *
     * @var array
     */
    public $config;

    /**
     * The I18n main object
     *
     * @var \go\I18n\I18n
     */
    public $i18n;

    /**
     * The list of languages (normal form)
     *
     * @var array
     */
    public $languages;

    /**
     * The default language
     *
     * @var string
     */
    public $default;

    /**
     * The current language
     *
     * @var string
     */
    public $current;

    /**
     * Constructor
     *
     * @param \go\I18n\I18n $i18n
     * @param array $config
     * @throws \go\I18n\Exceptions\ConfigInvalid
     */
    public function __construct(\go\I18n\I18n $i18n, array $config)
    {
        $this->i18n = $i18n;
        $this->config = $config;
        if (!isset($config['default'])) {
            throw new ConfigInvalid('The field "default" is not specified');
        }
        $this->default = $config['default'];
        if ((!isset($config['languages'])) || (!\is_array($config['languages']))) {
            throw new ConfigInvalid('The field "languages" is not specified');
        }
        $this->languages = ConfigNormalizer::languagesNormalize($config['languages'], $this->default);
    }
}
