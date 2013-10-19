<?php
/**
 * IDeclension implementation: functions in the associative array
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

class Dict extends Base
{
    /**
     * @override \go\I18n\Declension\Base
     */
    protected function init()
    {
        if (isset($this->params['funcs'])) {
            $this->funcs = $this->params['funcs'];
        } else {
            $this->funcs = array();
        }
    }

    /**
     * @override \go\I18n\Declension\Base
     *
     * @param string $language
     * @return callable|NULL
     */
    protected function loadLocale($language)
    {
        return isset($this->funcs[$language]) ? $this->funcs[$language] : null;
    }

    /**
     * @var array
     */
    private $funcs;
}
