<?php

namespace go\I18n\UI;

class Php extends File
{
    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function loadData()
    {
        return $this->context->getIO()->execPhpFile($this->filename);
    }
}
