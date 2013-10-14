<?php
/**
 * The basic implementation of the IMultiItem interface
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Items;

class MultiItem implements IMultiItem
{
    /**
     * Constructor
     *
     * @param \go\I18n\Items\IMultiType $type
     * @param string|int $cid
     */
    public function __construct(\go\I18n\Items\IMultiType $type, $cid)
    {
        $this->type = $type;
        $this->cid = $cid;
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @return \go\I18n\Items\IMultiType
     */
    public function getMultiType()
    {
        return $this->type;
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @return string|int
     */
    public function getCID()
    {
        return $this->cid;
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $language
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function getLocal($language)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     */
    public function remove()
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $key
     * @return \go\I18n\Items\ILocalItem
     * @throws \go\I18n\Exceptions\LanguageNotExists
     */
    public function __get($key)
    {

    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {

    }

    /**
     * @var string|int
     */
    protected $cid;

    /**
     * @var \go\I18n\Items\IMultiType
     */
    protected $type;
}
