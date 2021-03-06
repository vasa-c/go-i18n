<?php
/**
 * Implementation of a root node of UI (all data in a single dir)
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\UI;

class RootSingleDir extends Root
{
    /**
     * @override \go\I18n\UI\Root
     */
    protected function init()
    {
        if (!isset($this->params['dirname'])) {
            throw new \go\I18n\Exceptions\ConfigService('UI', 'Required "dirname"');
        }
        $this->dirname = $this->params['dirname'];
    }

    /**
     * @override \go\I18n\UI\Root
     *
     * @package string $language
     * @return mixed|null
     */
    protected function createLocaleUI($language)
    {
        $ui = $this->context->adaptersUI->createNode($language, $this->dirname, $language, '');
        if ($ui === null) {
            return $this->context->adaptersUI->createEmptyDataNode($language, '');
        }
        return $ui;
    }

    /**
     * @var string
     */
    private $dirname;
}
