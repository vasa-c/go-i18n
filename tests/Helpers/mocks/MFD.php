<?php
/**
 * The mock for test of magic fields (dynamic fields)
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers\mocks;

/**
 * @property-read string $f_static
 * @property-read object $f_dynamic
 */
class MFD extends \go\I18n\Helpers\MagicFields
{
    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @var string
     */
    protected $magicFields = array(
        'f_static' => true,
    );

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return mixed
     * @throws \go\I18n\Exceptions\FieldNotFound
     */
    protected function magicFieldCreate($key)
    {
        switch ($key) {
            case 'f_static':
                return 'Static';
            case 'f_dynamic':
                return (object)array('y' => 2);
            case 'unknown':
                return 'exists';
        }
    }

    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @param string $key
     * @return boolean
     */
    protected function magicFieldIsset($key)
    {
        if ($key === 'f_dynamic') {
            return true;
        }
        return false;
    }
}
