<?php

namespace go\I18n\UI;

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
            if (isset($value[0])) {
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
     * @var array
     */
    protected $data;

    /**
     * @var \go\I18n\UI\Adapters
     */
    protected $adapters;
}
