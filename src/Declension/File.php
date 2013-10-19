<?php
/**
 * IDeclension implementation: functions in the file
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

class File extends Base
{
    /**
     * @override \go\I18n\Declension\Base
     */
    protected function init()
    {
        if (!isset($this->params['filename'])) {
            throw new \go\I18n\Exceptions\ConfigInvalid('Declension File required "filename" field');
        }
        $this->filename = $this->params['filename'];
    }

    /**
     * @override \go\I18n\Declension\Base
     *
     * @param string $language
     * @return callable|NULL
     */
    protected function loadLocale($language)
    {
        if (!$this->funcs) {
            $this->funcs = $this->context->getIO()->execPhpFile($this->filename);
        }
        return isset($this->funcs[$language]) ? $this->funcs[$language] : null;
    }

    /**
     * @var array
     */
    private $funcs;

    /**
     * @var string
     */
    private $filename;
}
