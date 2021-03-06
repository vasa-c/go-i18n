<?php
/**
 * Basic implementation for a root node of UI
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

abstract class Root extends Base
{
    /**
     * Constructor
     *
     * @param array $params
     * @param \go\I18n\Helpers\Context $context
     */
    public function __construct(\go\I18n\Helpers\Context $context, array $params)
    {
        parent::__construct($context, '', null);
        $this->params = $params;
        $adapters = isset($params['adapters']) ? $params['adapters'] : null;
        Adapters::createUIAdapters($context, $adapters);
        $this->init();
    }

    /**
     * Init node and check parameters (for override)
     */
    protected function init()
    {
    }

    /**
     * Try load a node by the key
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        if (!isset($this->context->languages[$key])) {
            return false;
        }
        $child = $this->createLocaleUI($key);
        if (\is_null($child)) {
            return false;
        }
        $this->childs[$key] = $child;
        return true;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @param string $key
     * @return \go\I18n\UI\INode|null
     */
    protected function loadFromParent($key)
    {
        return false;
    }

    /**
     * Create an UI service for specified locale
     *
     * @package string $language
     * @return mixed|null
     */
    abstract protected function createLocaleUI($language);

    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    public function asArray()
    {
        $data = array();
        foreach ($this->context->languages as $key => $v) {
            $data[$key] = $this->__get($key)->localAsArray();
        }
        return $data;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function localAsArray()
    {
        throw new \LogicException('localAsArray() is not defined for Root-UI');
    }

    /**
     * @var array
     */
    protected $params;
}
