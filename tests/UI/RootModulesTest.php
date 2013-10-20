<?php
/**
 * Test of the RootModules implementation of an UI root node
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\UI;

/**
 * @covers go\I18n\UI
 */
class RootModulesTest extends \PHPUnit_Framework_TestCase
{
    public function testRootModules()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'ui' => array(
                'classname' => 'RootModules',
                'modules' => array(
                    'news' => true,
                    'articles' => true,
                    'pages' => true,
                ),
                'pattern_dir' => __DIR__.'/testmodules/{{ module }}/ui',
            ),
        );
        $i18n = new \go\I18n\I18n($config);
        $locale = $i18n->getLocale('ru');
        $ui = $locale->ui;
        $this->assertTrue(isset($ui->news));
        $this->assertTrue(isset($ui->articles));
        $this->assertTrue(isset($ui->pages));
        $this->assertFalse(isset($ui->polls));
        $this->assertEquals('Новости', $ui->news->title);
        $this->assertEquals('Показать новость', $ui->news->view);
        $this->assertEquals('pages', $ui->news->pages);
        $this->assertEquals('this is page', $ui->news->page);
        $this->assertEquals('Articles', $ui->articles->title);
        $this->assertEquals('News', $i18n->getLocale('en')->ui->news->title);
    }

    public function testAsArray()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'ui' => array(
                'classname' => 'RootModules',
                'modules' => array(
                    'news' => true,
                    'articles' => true,
                    'pages' => true,
                ),
                'pattern_dir' => __DIR__.'/testmodules/{{ module }}/ui',
            ),
        );
        $i18n = new \go\I18n\I18n($config);
        $locale = $i18n->getLocale('ru');
        $ui = $locale->ui;
        $expected = array(
            'news' => array(
                'title' => 'Новости',
                'view' => 'Показать новость',
                'pages' => 'pages',
                'page' => 'this is page',
            ),
            'articles' => array(
                'title' => 'Articles',
            ),
            'pages' => array(
            ),
        );
        $this->assertEquals($expected, $ui->asArray());
    }
}
