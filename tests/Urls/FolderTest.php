<?php
/**
 * Test of the folder version of urls
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Urls;

use go\I18n\I18n;

/**
 * @covers go\I18n\Urls\Folder
 */
class FolderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testConfig = array(
        'languages' => array(
            'en' => true,
            'ru' => true,
            'de' => true,
        ),
        'default' => 'en',
        'urls' => array(
            'urls' => array(
                'admin/in/' => true,
                'admin/' => false,
                'ajax' => false,
            ),
        ),
    );

    /**
     * @var array
     */
    private $testParams = array(
        'REQUEST_URI' => '',
        'PHP_SAPI' => 'cgi',
        'HTTP_HOST' => 'example.loc',
        'HTTPS' => false,
    );

    public function testCreateUrls()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $this->assertInstanceOf('go\I18n\Urls\IUrls', $urls);
    }

    public function testResolveNormal()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $this->assertNull($urls->getResolveResult());
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/about/company/?comment=5';
        $result = $urls->resolve($params);
        $this->assertEquals($result, $urls->getResolveResult());
        $expected = array(
            'language' => 'ru',
            'multi' => true,
            'redirect' => false,
            'rel_url' => 'about/company/?comment=5',
        );
        $this->assertEquals($expected, $result);
        $this->assertEquals('ru', $i18n->getCurrentLanguage());
    }

    public function testResolveSingle()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/admin/news/?edit=5';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'en',
            'multi' => false,
            'redirect' => false,
            'rel_url' => 'admin/news/?edit=5',
        );
        $this->assertEquals($expected, $result);
    }

    public function testResolveMultiInSingle()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/admin/in/';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'ru',
            'multi' => true,
            'redirect' => false,
            'rel_url' => 'admin/in/',
        );
        $this->assertEquals($expected, $result);
    }

    public function testRoot()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'en',
            'multi' => true,
            'redirect' => 'http://example.loc/en/',
            'rel_url' => '',
        );
        $this->assertEquals($expected, $result);
    }

    public function testRedirectPage()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/page/?x=1';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'en',
            'multi' => true,
            'redirect' => 'http://example.loc/en/page/?x=1',
            'rel_url' => 'page/?x=1',
        );
        $this->assertEquals($expected, $result);
    }

    public function testUserDef()
    {
        $config = $this->testConfig;
        $config['urls']['user_def'] = function () {
            return 'de';
        };
        $i18n = new I18n($config);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/page/?x=1';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'de',
            'multi' => true,
            'redirect' => 'http://example.loc/de/page/?x=1',
            'rel_url' => 'page/?x=1',
        );
        $this->assertEquals($expected, $result);
    }

    public function testRedirectSinglePage()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/admin/news/?x=1';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'en',
            'multi' => false,
            'redirect' => 'http://example.loc/admin/news/?x=1',
            'rel_url' => 'admin/news/?x=1',
        );
        $this->assertEquals($expected, $result);
    }

    public function testCLI()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['PHP_SAPI'] = 'cli';
        $result = $urls->resolve($params);
        $expected = array(
            'language' => 'en',
            'multi' => false,
            'redirect' => false,
            'rel_url' => null,
        );
        $this->assertEquals($expected, $result);
    }

    public function testUrl()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $result = $urls->resolve($params);

        $actual = $urls->url('news/view/?id=5', null, false, 'ru');
        $expected = '/ru/news/view/?id=5';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('news/view/?id=5');
        $expected = '/ru/news/view/?id=5';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('news/view/?id=5', null, false, 'en');
        $expected = '/en/news/view/?id=5';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('/news/view/?id=5', null, false, 'en');
        $expected = '/news/view/?id=5';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('admin/news/edit/?id=5', null, false, 'ru');
        $expected = '/admin/news/edit/?id=5';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('admin/in/edit/?id=5', null, false, 'ru');
        $expected = '/ru/admin/in/edit/?id=5';
        $this->assertEquals($expected, $actual);
    }

    public function testUrlData()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $urls->resolve($params);

        $actual = $urls->url('page/', 'x=1&y=2');
        $expected = '/ru/page/?x=1&y=2';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('page/?z=3', 'x=1&y=2');
        $expected = '/ru/page/?z=3&x=1&y=2';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('page/?z=3', array('x' => 1, 'y' => 3));
        $expected = '/ru/page/?z=3&x=1&y=3';
        $this->assertEquals($expected, $actual);
    }

    public function testUrlAbsolute()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $urls->resolve($params);

        $actual = $urls->url('page/', 'x=1&y=2', true);
        $expected = 'http://example.loc/ru/page/?x=1&y=2';
        $this->assertEquals($expected, $actual);

        $actual = $urls->url('admin/', 'x=1&y=2', true);
        $expected = 'http://example.loc/admin/?x=1&y=2';
        $this->assertEquals($expected, $actual);
    }

    public function testHttps()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $params['HTTPS'] = true;
        $urls->resolve($params);

        $actual = $urls->url('page/', 'x=1&y=2', true);
        $expected = 'https://example.loc/ru/page/?x=1&y=2';
        $this->assertEquals($expected, $actual);
    }

    public function testLocale()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $urls->resolve($params);

        $actual = $i18n->getLocale('en')->url('page/?x=1', null, true);
        $expected = 'http://example.loc/en/page/?x=1';
        $this->assertEquals($expected, $actual);
    }

    public function testUseres()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $urls->resolve($params, false);
        $this->assertNull($i18n->getCurrentLanguage());
    }

    public function testErrorUrlsAlreadyResolved()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $urls->resolve($params);
        $this->setExpectedException('go\I18n\Exceptions\UrlsAlreadyResolved');
        $urls->resolve($params);
    }

    public function testErrorUrlsNotResolved()
    {
        $i18n = new I18n($this->testConfig);
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $this->setExpectedException('go\I18n\Exceptions\UrlsNotResolved');
        $urls->url('page/?x=1', null, true);
    }

    public function testErrorCurrentAlreadySpecified()
    {
        $i18n = new I18n($this->testConfig, 'it');
        $urls = $i18n->urls;
        $params = $this->testParams;
        $params['REQUEST_URI'] = '/ru/page/';
        $this->setExpectedException('go\I18n\Exceptions\CurrentAlreadySpecified');
        $urls->resolve($params);
    }
}
