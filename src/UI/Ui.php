<?php

namespace go\I18n\UI;

use go\I18n\Helpers\ParserUI;

class Ui extends File
{
    /**
     * @override \go\I18n\UI\Base
     *
     * @return array
     */
    protected function loadData()
    {
        return ParserUI::parseFile($this->filename, $this->context->getIO());
    }
}
