<?php
/**
 * Test of the locale class
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n;

/**
 * @covers go\I18n\Locale
 */
class LocaleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testConfig = array(
        'languages' => array(
            'en' => true,
            'ru' => true,
            'de' => true,
            'by' => 'ru',
        ),
        'default' => 'en',
    );

    /**
     * @param string $language
     * @param array $config
     * @return \go\I18n\Locale
     */
    private function createLocale($language = 'ru', $config = null)
    {
        if (!$config) {
            $config = $this->testConfig;
        }
        $i18n = new \go\I18n\I18n($config);
        return $i18n->getLocale($language);
    }

    /**
     * @cover go\I18n\Local::$language
     */
    public function testGetLanguage()
    {
        $local = $this->createLocale('by');
        $this->assertEquals('by', $local->language);
        $expected = array(
            'title' => 'by',
            'parent' => 'ru',
            'url' => 'by',
        );
        $this->assertEquals($expected, $local->paramsLanguage);
    }
}
