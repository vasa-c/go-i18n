<?php
/**
 * Test of the urls helper
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\I18n\Helpers\Urls;

/**
 * @covers go\I18n\Helpers\Urls
 */
class UrlsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\Urls::normalizeUrlsConfig
     * @dataProvider providerNormalizeUrlsConfig
     */
    public function testNormalizeUrlsConfig($config, $expected)
    {
        if (\is_null($expected)) {
            $this->setExpectedException('go\I18n\Exceptions\ConfigInvalid');
            Urls::normalizeUrlsConfig($config);
        } else {
            $this->assertEquals($expected, Urls::normalizeUrlsConfig($config));
        }
    }

    /**
     * @return array
     */
    public function providerNormalizeUrlsConfig()
    {
        return array(
            array(
                array(
                    'admin/' => false,
                    'ajax/qwerty/' => true,
                    'ajax/' => false,
                ),
                array(
                    'admin/' => false,
                    'ajax/qwerty/' => true,
                    'ajax/' => false,
                ),
            ),
            array(
                array(
                    'admin/' => false,
                    'ajax/' => false,
                    '' => true,
                ),
                array(
                    'admin/' => false,
                    'ajax/' => false,
                ),
            ),
            array(
                array(
                    'admin/' => false,
                    '' => true,
                    'ajax/' => false,
                ),
                array(
                    'admin/' => false,
                    '' => true,
                    'ajax/' => false,
                ),
            ),
            array(
                array(
                    'admin/' => false,
                    'ajax/' => true,
                    '' => false,
                ),
                array(
                    'admin/' => false,
                    'ajax/' => true,
                    '' => false,
                ),
            ),
            array(
                true,
                array(
                ),
            ),
            array(
                false,
                array(
                    '' => false,
                ),
            ),
            array(
                'string',
                null,
            ),
            array(
                null,
                array(),
            ),
        );
    }

    /**
     * @covers go\I18n\Helpers\Urls::defineVersion
     */
    public function testDefineVersion()
    {
        $config = array(
            'admin/in/' => true,
            'admin/' => false,
            'ajax/' => false,
        );
        $this->assertTrue(Urls::defineVersion($config, ''));
        $this->assertTrue(Urls::defineVersion($config, 'news/view/'));
        $this->assertTrue(Urls::defineVersion($config, 'admin/in/'));
        $this->assertTrue(Urls::defineVersion($config, 'admin/in/edit/?x=1'));
        $this->assertFalse(Urls::defineVersion($config, 'admin/'));
        $this->assertFalse(Urls::defineVersion($config, 'admin/edit/?x=1'));
        $this->assertFalse(Urls::defineVersion($config, 'ajax/?x=5'));
        $config[''] = false;
        $this->assertFalse(Urls::defineVersion($config, ''));
        $this->assertFalse(Urls::defineVersion($config, 'news/view/'));
        $this->assertTrue(Urls::defineVersion($config, 'admin/in/'));
        $this->assertTrue(Urls::defineVersion($config, 'admin/in/edit/?x=1'));
        $this->assertFalse(Urls::defineVersion($config, 'admin/'));
        $this->assertFalse(Urls::defineVersion($config, 'admin/edit/?x=1'));
        $this->assertFalse(Urls::defineVersion($config, 'ajax/?x=5'));
    }
}
