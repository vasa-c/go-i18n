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
    /**
     * @covers go\I18n\I18n::__construct
     * @covers go\I18n\I18n::getListLanguages
     * @covers go\I18n\I18n::getDefaultLanguage
     */
    public function testConstructAndInfo()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
                'it' => true,
            ),
            'default' => 'en',
        );
        $i18n = new I18n($config);
        $this->assertEquals(array('en', 'ru', 'it'), $i18n->getListLanguages());
        $this->assertEquals('en', $i18n->getDefaultLanguage());
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
}
