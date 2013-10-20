<?php

namespace go\I18n\UI;

class LocalModules extends Base
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param string $language
     * @param \go\I18n\Helpers\Modules $modules
     */
    public function __construct($context, $language, $modules)
    {
        parent::__construct($context, '', $language);
        $this->modules = $modules;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function localAsArray()
    {
        $result = array();
        foreach ($this->modules->getListModules() as $key) {
            $child = $this->__get($key);
            if ($child instanceof IAmArray) {
                $child = $child->asArray();
            }
            $result[$key] = $child;
        }
        return $result;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        $dirname = $this->modules->getDir($key);
        if (!$dirname) {
            return false;
        }
        $io = $this->context->getIO();
        if (!$io->isDir($dirname)) {
            $node = new EmptyData($this->context, $key, $this->language);
            $this->childs[$key] = $node;
            return true;
        }
        $adapters = $this->context->adaptersUI;
        $node = $adapters->createNode($this->language, $dirname, $this->language, $key);
        if (!$node) {
            $node = new EmptyData($this->context, $key, $this->language);
        }
        $this->childs[$key] = $node;
        return true;
    }

    /**
     *
     * @var \go\I18n\UI\Helpers\Modules
     */
    private $modules;
}
