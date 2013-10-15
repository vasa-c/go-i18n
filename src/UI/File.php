<?php

namespace go\I18n\UI;

abstract class File extends Single
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param string $key
     * @param string $filename
     * @param \go\I18n\UI\INode $inline [optional]
     */
    public function __construct($context, $key, $language, $filename, $inline = null)
    {
        parent::__construct($context, $key, $language);
        $this->filename = $filename;
        $this->inline = $inline;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @param string $key
     * @return boolean
     */
    protected function loadTry($key)
    {
        if (parent::loadTry($key)) {
            return true;
        }
        if ($this->inline) {
            if (!$this->inline->__isset($key)) {
                return false;
            }
            $this->childs[$key] = $this->inline->__get($key);
            return true;
        }
        return false;
    }

    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function localAsArray()
    {
        $data = parent::localAsArray();
        if ($this->inline) {
            $inline = $this->inline->localAsArray();
            $data = \array_replace_recursive($inline, $data);
        }
        return $data;
    }

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var \go\I18n\UI\INode
     */
    protected $inline;
}
