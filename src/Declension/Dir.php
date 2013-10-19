<?php
/**
 * IDeclension implementation: functions in the file
 *
 * @package go\I18n
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\I18n\Declension;

class Dir extends Base
{
    /**
     * @override \go\I18n\Declension\Base
     */
    protected function init()
    {
        if (!isset($this->params['dirname'])) {
            throw new \go\I18n\Exceptions\ConfigInvalid('Declension File required "dirname" field');
        }
        $this->dirname = $this->params['dirname'];
    }

    /**
     * @override \go\I18n\Declension\Base
     *
     * @param string $language
     * @return callable|NULL
     */
    protected function loadLocale($language)
    {
        $filename = $this->dirname.'/'.$language.'.php';
        $io = $this->context->getIO();
        if (!$io->isFile($filename)) {
            return null;
        }
        return $io->execPhpFile($filename);
    }

    /**
     * @var string
     */
    private $dirname;
}
