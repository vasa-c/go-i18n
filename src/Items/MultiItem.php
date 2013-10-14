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
     * @param \go\I18n\Helpers\Context $context
     * @param \go\I18n\Items\IMultiType $type
     * @param string|int $cid
     */
    public function __construct($context, \go\I18n\Items\IMultiType $type, $cid)
    {
        $this->context = $context;
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
        if (!isset($this->locales[$language])) {
            $this->context->mustLanguageExists($language);
            $this->locales[$language] = new LocalItem($this, $language, $this->cid);
        }
        return $this->locales[$language];
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     */
    public function remove()
    {
        $this->type->removeItem($this->cid);
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     */
    public function resetCache()
    {
        foreach ($this->locales as $litem) {
            $litem->resetCache();
        }
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
        return $this->getLocal($key);
    }

    /**
     * @override \go\I18n\Items\IMultiItem
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->context->languages[$key]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '[I18N:MultiItem:'.$this->type->getName().':'.$this->cid.']';
    }

    /**
     * @var string|int
     */
    protected $cid;

    /**
     * @var \go\I18n\Items\IMultiType
     */
    protected $type;

    /**
     * @var \go\I18n\Helpers\Context
     */
    protected $context;

    /**
     * @var array
     */
    protected $locales = array();
}
