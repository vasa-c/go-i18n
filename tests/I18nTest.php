<?php
/**
 * Test of I18n main class
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n;

use go\I18n\I18n;

/**
 * @covers go\I18n\I18n
 */
class I18nTest extends \PHPUnit_Framework_TestCase
{
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
     * @covers go\I18n\I18n::__construct
     * @covers go\I18n\I18n::getListLanguages
     * @covers go\I18n\I18n::getDefaultLanguage
     * @covers go\I18n\I18n::isLanguageExists
     */
    public function testConstructAndInfo()
    {
        $i18n = new I18n($this->testConfig);
        $this->assertEquals(array('en', 'ru', 'de', 'by'), $i18n->getListLanguages());
        $this->assertEquals('en', $i18n->getDefaultLanguage());
        $this->assertTrue($i18n->isLanguageExists('ru'));
        $this->assertFalse($i18n->isLanguageExists('jp'));
    }

    /**
     * @covers go\I18n\I18n::__construct
     * @param array $config
     * @dataProvider providerConstructError
     * @expectedException go\I18n\Exceptions\ConfigInvalid
     */
    public function testConstructError(array $config)
    {
        return new I18n($config);
    }

    /**
     * @return array
     */
    public function providerConstructError()
    {
        return array(
            array(
                array( // languages is not specified
                    'default' => 'en',
                ),
            ),
            array(
                array( // default is not specified
                    'languages' => array(
                        'en' => true,
                    ),
                ),
            ),
            array(
                array( // language is not array
                    'languages' => 'scalar',
                    'default' => 'en',
                ),
            ),
        );
    }

    /**
     * @covers go\I18n\I18n::__construct
     * @covers go\I18n\I18n::getCurrentLanguage
     * @covers go\I18n\I18n::setCurrentLanguage
     */
    public function testCurrentLanguage()
    {
        $i18n1 = new I18n($this->testConfig);
        $this->assertNull($i18n1->getCurrentLanguage());
        $i18n1->setCurrentLanguage('en');
        $this->assertEquals('en', $i18n1->getCurrentLanguage());
        try {
            $i18n1->setCurrentLanguage('en');
            $this->fail('CurrentAlreadySpecified has not been raised');
        } catch (\go\I18n\Exceptions\CurrentAlreadySpecified $e) {
            $this->assertNotEmpty($e);
        }

        $i18n2 = new I18n($this->testConfig, 'ru');
        $this->assertEquals('ru', $i18n2->getCurrentLanguage());
        try {
            $i18n2->setCurrentLanguage('en');
            $this->fail('CurrentAlreadySpecified has not been raised');
        } catch (\go\I18n\Exceptions\CurrentAlreadySpecified $e) {
            $this->assertNotEmpty($e);
        }

        $i18n3 = new I18n($this->testConfig);
        try {
            $i18n3->setCurrentLanguage('it');
            $this->fail('LanguageNotExists has not been raised');
        } catch (\go\I18n\Exceptions\LanguageNotExists $e) {
            $this->assertEquals('it', $e->getLanguage());
        }
    }

    /**
     * @covers go\I18n\I18n::getLocale
     */
    public function testGetLocale()
    {
        $i18n = new I18n($this->testConfig);
        $en = $i18n->getLocale('en');
        $ru = $i18n->getLocale('ru');
        $this->assertInstanceOf('go\I18n\Locale', $en);
        $this->assertEquals('en', $en->language);
        $this->assertEquals('ru', $ru->language);
        $this->assertSame($ru, $i18n->getLocale('ru'), 'Cache locals');
        $this->assertSame($i18n, $ru->i18n);

        $this->setExpectedException('go\I18n\Exceptions\LanguageNotExists');
        $i18n->getLocale('it');
    }

    /**
     * @covers go\I18n\I18n::getCurrentLocale
     * @covers go\I18n\I18n::__get
     */
    public function testGetCurrentLocale()
    {
        $i18n = new I18n($this->testConfig, 'de');
        $current = $i18n->getCurrentLocale();
        $this->assertEquals('de', $current->language);
        $this->assertEquals($current, $i18n->current);

        $i18n2 = new I18n($this->testConfig);
        $this->setExpectedException('go\I18n\Exceptions\CurrentNotSpecified');
        $i18n2->getCurrentLocale();
    }

    /**
     * @covers go\I18n\I18n::__get
     * @covers go\I18n\I18n::__isset
     */
    public function testMagicGetLocales()
    {
        $i18n = new I18n($this->testConfig);
        $this->assertTrue(isset($i18n->en));
        $this->assertTrue(isset($i18n->ru));
        $this->assertFalse(isset($i18n->jp));
        $this->assertEquals('ru', $i18n->ru->language);
        $this->assertEquals($i18n->en, $i18n->en);
        $this->setExpectedException('go\I18n\Exceptions\FieldNotFound');
        return $i18n->jp;
    }
}
