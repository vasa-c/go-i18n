<?php

namespace go\I18n\UI;

class Dir extends Base
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param string $key
     * @param string $dirname
     * @param \go\I18n\UI\INode $inline [optional]
     */
    public function __construct($context, $key, $language, $dirname)
    {
        parent::__construct($context, $key, $language);
        $this->dirname = $dirname;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        $adapters = $this->context->adaptersUI;
        $node = $adapters->createNode($this->language, $this->dirname, $key, $this->pkey.$key);
        if ($node === null) {
            return false;
        }
        $this->childs[$key] = $node;
        return true;
    }

    /**
     * @var string
     */
    private $dirname;
}
