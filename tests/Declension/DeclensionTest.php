<?php
/**
 * Test of the declension service
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Declension;

use go\I18n\I18n;

class DeclensionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $config = array(
            'languages' => array(
                'en' => true,
            ),
            'default' => 'en',
        );
        $i18n = new I18n($config);
        $this->assertInstanceOf('go\I18n\Declension\IDeclension', $i18n->declension);
    }

    public function testDefault()
    {
        $config = array(
            'languages' => array(
                'en' => true,
            ),
            'default' => 'en',
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;
        $this->assertEquals('object', $declension->decline(5, array('object')));
        $this->assertEquals('objects', $declension->decline(5, array('object', 'objects')));
        $this->assertEquals('object', $declension->decline(1, array('object', 'objects')));
        $this->assertEquals('объект', $declension->decline(1, array('объект', 'объекта', 'объектов')));
        $this->assertEquals('объекта', $declension->decline(2, array('объект', 'объекта', 'объектов')));
        $this->assertEquals('объектов', $declension->decline(12, array('объект', 'объекта', 'объектов')));
    }

    public function testDict()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'declension' => array(
                'funcs' => array(
                    'ru' => function ($number, $forms) {
                        return 'rus'.$number;
                    },
                    'en' => function ($number, $forms) {
                        return 'eng'.$number;
                    },
                ),
            ),
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;
        $this->assertEquals('rus5', $declension->decline(5, array(), 'ru'));
        $this->assertEquals('eng6', $declension->decline(6, array(), 'en'));
    }

    public function testFile()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'declension' => array(
                'classname' => 'File',
                'filename' => __DIR__.'/files/file.php',
            ),
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;
        $this->assertEquals('c', $declension->decline(11, array('a', 'b', 'c'), 'ru'));
        $this->assertEquals('b', $declension->decline(11, array('a', 'b', 'c'), 'en'));
    }

    public function testDir()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'declension' => array(
                'classname' => 'Dir',
                'dirname' => __DIR__.'/files/dir',
            ),
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;
        $this->assertEquals('dir-rus-11', $declension->decline(11, array('a', 'b', 'c'), 'ru'));
        $this->assertEquals('dir-eng-11', $declension->decline(11, array('a', 'b', 'c'), 'en'));
    }

    public function testInherit()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
                'by' => 'ru',
            ),
            'default' => 'en',
            'declension' => array(
                'funcs' => array(
                    'ru' => function ($number, $forms) {
                        return 'rus-'.$number;
                    },
                ),
            ),
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;
        $this->assertEquals('c', $declension->decline(11, array('a', 'b', 'c'), 'en'));
        $this->assertEquals('rus-11', $declension->decline(11, array('a', 'b', 'c'), 'ru'));
        $this->assertEquals('rus-11', $declension->decline(11, array('a', 'b', 'c'), 'by'));
    }

    public function testUIKey()
    {
        $config = array(
            'languages' => array(
                'en' => true,
            ),
            'default' => 'en',
            'ui' => array(
                'dirname' => __DIR__.'/files/ui',
            ),
        );
        $i18n = new I18n($config);
        $declension = $i18n->declension;

        $this->assertEquals('one', $declension->decline(1, 'dec.list'));
        $this->assertEquals('two', $declension->decline(2, 'dec.list'));
        $this->assertEquals('two', $declension->decline(3, 'dec.list'));
        $this->assertEquals('three', $declension->decline(5, 'dec.list'));
        /*
        $this->assertEquals('first', $declension->decline(1, 'dec.objects'));
        $this->assertEquals('second', $declension->decline(2, 'dec.objects'));
        $this->assertEquals('second', $declension->decline(3, 'dec.objects'));
        $this->assertEquals('third', $declension->decline(5, 'dec.objects'));
         */
    }

    public function testLocale()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'declension' => array(
                'funcs' => array(
                    'ru' => function ($number, $forms) {
                        return 'rus'.$number;
                    },
                    'en' => function ($number, $forms) {
                        return 'eng'.$number;
                    },
                ),
            ),
        );
        $i18n = new I18n($config);
        $this->assertEquals('rus10', $i18n->getLocale('ru')->decline(10, array()));
        $this->assertEquals('eng11', $i18n->getLocale('en')->decline(11, array()));
    }
    /*
    public function testUINode()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'ui' => array(
                'dirname' => __DIR__.'/files/ui',
            ),
        );
        $i18n = new I18n($config);
        $locale = $i18n->getLocale('ru');
        $this->assertEquals('third', $locale->ui->dec->objects->decline(5));
    }
    */
}
