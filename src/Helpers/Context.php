<?php
/**
 * The context is shared between all i18n-services
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Helpers;

use go\I18n\Exceptions\ConfigInvalid;
use go\I18n\Exceptions\LanguageNotExists;

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
     * The cache of locale instances
     *
     * @var array
     */
    public $locales = array();

    /**
     * The user interface service
     *
     * @var \go\I18n\UI\INode
     */
    public $ui;

    /**
     * The list of UI adapters (created when UI created)
     *
     * @var \go\I18n\UI\Adapters
     */
    public $adaptersUI;

    /**
     * The items service
     *
     * @var \go\I18n\Items\IMultiContainer
     */
    public $items;

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

    /**
     * A language exists or die
     *
     * @param string $language
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function mustLanguageExists($language)
    {
        if (!isset($this->languages[$language])) {
            throw new LanguageNotExists($language);
        }
    }

    /**
     * Get the IO-implementation for this system
     *
     * @return \go\I18n\IO\IIO
     */
    public function getIO()
    {
        if (!$this->io) {
            $config = isset($this->config['io']) ? $this->config['io'] : null;
            $options = array(
                'default' => 'go\I18n\IO\Native',
                'base' => 'go\I18n\IO\IIO',
                'key' => 'io',
            );
            $this->io = Creator::create($config, $options);
        }
        return $this->io;
    }

    /**
     * Get the user interface service
     *
     * @return \go\I18n\UI\INode
     */
    public function getUI()
    {
        if (!$this->ui) {
            $config = isset($this->config['ui']) ? $this->config['ui'] : null;
            $options = array(
                'default' => 'go\I18n\UI\RootSingleDir',
                'base' => 'go\I18n\UI\INode',
                'key' => 'ui',
                'args' => array($this),
            );
            $this->ui = Creator::create($config, $options);
        }
        return $this->ui;
    }

    /**
     * Get the items service
     *
     * @return \go\I18n\Items\IMultiContainer
     */
    public function getItems()
    {
        if (!$this->items) {
            $config = isset($this->config['items']) ? $this->config['items'] : null;
            $options = array(
                'default' => 'go\I18n\Items\MultiContainer',
                'base' => 'go\I18n\Items\IMultiContainer',
                'key' => 'items',
                'args' => array($this, ''),
            );
            $this->items = Creator::create($config, $options);
        }
        return $this->items;
    }

    /**
     * @var \go\I18n\IO\IIO
     */
    private $io;
}
