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

    /**
     * @covers go\I18n\Locale::$ui
     */
    public function testUI()
    {
        $config = $this->testConfig;
        $config['ui'] = array(
            'dirname' => __DIR__.'/UI/testui',
        );
        $i18n = new \go\I18n\I18n($config);
        $this->assertSame($i18n->ui->ru, $i18n->ru->ui);
    }

    /**
     * @covers go\I18n\Locale::$items
     */
    public function testItems()
    {
        $config = $this->testConfig;
        $i18n = new \go\I18n\I18n($config);
        $locale = $i18n->getLocale('ru');
        $items = $locale->items;
        $this->assertInstanceOf('go\I18n\Items\ILocalContainer', $items);
        $this->assertSame($i18n->items->ru, $items);
    }

    public function testEmptyLocale()
    {
        $config = $this->testConfig;
        $config['ui'] = array(
            'dirname' => __DIR__.'/UI/testui',
        );
        $i18n = new \go\I18n\I18n($config);
        $i18n->setCurrentLanguage(null);
        $current = $i18n->getCurrentLocale();
        $this->assertTrue($i18n->isEmptyLocaleMode());
        $this->assertSame($current, $i18n->current);
        $this->assertTrue($current->isCurrent());
        $this->assertSame($i18n, $current->i18n);
        $this->assertTrue($current->isEmpty());
        $ui = $i18n->ui;
        $i18n->setCurrentLanguage('ru');
        $this->assertFalse($i18n->isEmptyLocaleMode());
        $this->assertSame($current, $i18n->current);
        $this->assertSame($current, $i18n->getCurrentLocale());
        $this->assertTrue($current->isCurrent());
        $this->assertFalse($current->isDefault());
        $this->assertEquals('ru', $current->language);
        $this->assertSame($i18n, $current->i18n);
        $this->assertSame($current, $i18n->getLocale('ru'));
        $this->assertSame($ui->ru, $current->ui);
        $this->assertFalse($current->isEmpty());
    }

    /**
     * @dataProvider providerEmptyLocaleError
     */
    public function testEmptyLocaleError($func)
    {
        $config = $this->testConfig;
        $i18n = new \go\I18n\I18n($config);
        $i18n->setCurrentLanguage(null);
        $current = $i18n->getCurrentLocale();
        $this->setExpectedException('go\I18n\Exceptions\LocaleEmptyMode');
        \call_user_func($func, $i18n, $current);
    }

    /**
     * @return array
     */
    public function providerEmptyLocaleError()
    {
        return array(
            array(
                function ($i18n, $locale) {
                    return $i18n->getLocale('en');
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $i18n->setCurrentLanguage(null);
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->isDefault();
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->language;
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->paramsLanguage;
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->parent;
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->ui;
                }
            ),
            array(
                function ($i18n, $locale) {
                    return $locale->items;
                }
            ),
        );
    }
}
