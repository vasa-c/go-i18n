<?php
/**
 * The basic implementation of ILocalContainer interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class LocalContainer implements ILocalContainer
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param \go\I18n\Items\IMultiContainer $multi
     * @param string $language
     */
    public function __construct(\go\I18n\Helpers\Context $context, \go\I18n\Items\IMultiContainer $multi, $language)
    {
        $this->context = $context;
        $this->multi = $multi;
        $this->language = $language;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getKey()
    {
        return $this->multi->getKey();
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @return \go\I18n\Items\IMultiContainer
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalContainer
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return \go\I18n\Items\ILocalType
     * @throws \go\I18n\Exceptions\ItemsChildNotFound
     */
    public function getType($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsSubcontainer($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string|array $path
     * @return boolean
     */
    public function existsType($path)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {

    }

    /**
     * @override \go\I18n\Items\ILocalContainer
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

    }

    /**
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var \go\I18n\Items\IMultiContainer
     */
    protected $multi;

    /**
     * @var string
     */
    protected $language;
}
