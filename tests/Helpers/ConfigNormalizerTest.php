<?php
/**
 * Test of the config normalize helper
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\I18n\Helpers\ConfigNormalizer;

/**
 * @covers go\I18n\Helpers\ConfigNormalizer
 */
class ConfigNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\ConfigNormalizer::languageNormalize
     */
    public function testLanguageNormalize()
    {
        $languages = array(
            'en' => array(
                'title' => 'English',
                'url' => 'en',
            ),
            'ru' => array(
                'url' => 'rus',
            ),
            'it' => true,
            'jp' => null,
            'by' => 'ru',
            'ua' => array(
                'title' => 'Ukranian',
                'parent' => 'ru',
            ),
        );
        $expected = array(
            'en' => array(
                'title' => 'English',
                'parent' => null,
                'url' => 'en',
            ),
            'ru' => array(
                'title' => 'ru',
                'parent' => 'en',
                'url' => 'rus',
            ),
            'it' => array(
                'title' => 'it',
                'parent' => 'en',
                'url' => 'it',
            ),
            'jp' => array(
                'title' => 'jp',
                'parent' => null,
                'url' => 'jp',
            ),
            'by' => array(
                'title' => 'by',
                'parent' => 'ru',
                'url' => 'by',
            ),
            'ua' => array(
                'title' => 'Ukranian',
                'parent' => 'ru',
                'url' => 'ua',
            ),
        );
        $actual = ConfigNormalizer::languagesNormalize($languages, 'en');
        $this->assertEquals($expected, $actual);
        $languages['en'] = true;
        $expected['en'] = array(
            'title' => 'en',
            'parent' => null,
            'url' => 'en',
        );
        $actual = ConfigNormalizer::languagesNormalize($languages, 'en');
        $this->assertEquals($expected, $actual);
    }
}
