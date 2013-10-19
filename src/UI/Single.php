<?php

namespace go\I18n\UI;

use go\I18n\Declension\UINode;

abstract class Single extends Base
{
    /**
     * @override \go\I18n\UI\Base
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        if ($this->data === null) {
            $this->data = $this->loadData();
        }
        if ($this->data === false) {
            return false;
        }
        if (!isset($this->data[$key])) {
            return false;
        }
        $value = $this->data[$key];
        if (\is_array($value)) {
            if (isset($value['__type'])) {
                $this->childs[$key] = new UINode($this->context, $value);
            } elseif (isset($value[0])) {
                $this->childs[$key] = $value;
            } else {
                if (!$this->adapters) {
                    $this->adapters = $this->context->adaptersUI;
                }
                $fkey = $this->pkey.$key;
                $this->childs[$key] = $this->adapters->createDataNode($this->language, $fkey, $value);
            }
        } else {
            $this->childs[$key] = $value;
        }
        return true;
    }

    /**
     * Load full data
     *
     * @return array
     */
    protected function loadData()
    {
        return array();
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function localAsArray()
    {
        if ($this->data === null) {
            $this->data = $this->loadData();
        }
        if ($this->data === false) {
            return array();
        }
        return $this->data;
    }

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \go\I18n\UI\Adapters
     */
    protected $adapters;
}
