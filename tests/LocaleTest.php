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
     * @cover go\I18n\Local::$paramsLanguage
     */
    public function testGetLanguage()
    {
        $locale = $this->createLocale('by');
        $this->assertEquals('by', $locale->language);
        $expected = array(
            'title' => 'by',
            'parent' => 'ru',
            'url' => 'by',
        );
        $this->assertEquals($expected, $locale->paramsLanguage);
        $this->assertTrue(isset($locale->language));
        $this->assertFalse(isset($locale->unknown));
    }

    /**
     * @cover go\I18n\Locale::isCurrent
     */
    public function testIsCurrent()
    {
        $i18n = new \go\I18n\I18n($this->testConfig);
        $ru = $i18n->getLocale('ru');
        $en = $i18n->getLocale('en');
        $this->assertFalse($ru->isCurrent());
        $this->assertFalse($en->isCurrent());
        $i18n->setCurrentLanguage('ru');
        $this->assertTrue($ru->isCurrent());
        $this->assertFalse($en->isCurrent());
    }

    /**
     * @covers go\I18n\Locale::isDefault
     */
    public function testIsDefault()
    {
        $i18n = new \go\I18n\I18n($this->testConfig);
        $this->assertTrue($i18n->getLocale('en')->isDefault());
        $this->assertFalse($i18n->getLocale('ru')->isDefault());
    }

    /**
     * @covers go\I18n\Locale::$parent
     */
    public function testParent()
    {
        $i18n = new \go\I18n\I18n($this->testConfig);
        $by = $i18n->getLocale('by');
        $this->assertEquals('ru', $by->parent->language);
        $this->assertEquals('en', $by->parent->parent->language);
        $this->assertNull($by->parent->parent->parent);
    }
}
