<?php
/**
 * Implementation of a root node of UI (data separated in modules)
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

class RootModules extends Root
{
    /**
     * @override \go\I18n\UI\Root
     *
     * @package string $language
     * @return mixed|null
     */
    protected function createLocaleUI($language)
    {
        if (!$this->modules) {
            $this->modules = new Helpers\Modules($this->params);
        }
        return new LocalModules($this->context, $language, $this->modules);
    }

    /**
     * @var \go\I18n\Helpers\Modules
     */
    private $modules;
}
