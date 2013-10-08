<?php

namespace go\I18n\UI;

class Data extends Single
{
    /**
     * Constructor
     *
     * @param \go\I18n\Helpers\Context $context
     * @param string $key
     * @param string $language
     * @param array $data
     */
    public function __construct($context, $key, $language, array $data)
    {
        parent::__construct($context, $key, $language);
        $this->data = $data;
    }
}
