<?php
/**
 * The mock for test of magic fields
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers\mocks;

/**
 * @property-read string $test_str
 * @property-read object $test_cache
 */
class MF extends \go\I18n\Helpers\MagicFields
{
    /**
     * @override \go\I18n\Helpers\MagicFields
     *
     * @var string
     */
    protected $magicFields = array(
        'test_str' => true,
        'test_cache' => true,
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
            case 'test_str':
                return 'String';
            case 'test_cache':
                return (object)array('x' => 1);
        }
    }
}
