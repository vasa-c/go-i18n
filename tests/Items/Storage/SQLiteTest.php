<?php
/**
 * Test of the SQLite adapter for storage (and all DB-adpaters on this example)
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items\Storage;

use go\I18n\Items\Storage\SQLite;

/**
 * @covers go\I18n\Items\Storage\SQLite
 */
class SQLiteTest extends \PHPUnit_Framework_TestCase
{
    public function testSQLite()
    {
        if (!\extension_loaded('sqlite3')) {
            $this->markTestSkipped('The php-extension sqlite3 is not loaded');
        }
        $db = new \SQLite3(':memory:');
        $dump = \file_get_contents(__DIR__.'/sqlite-install.sql');
        $db->query($dump);

        $params = array(
            'db' => $db,
            'table' => 'items',
            'cols' => array(
                'language' => 'lg',
            ),
        );
        $storage = new SQLite($params);

        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'en', 10);
        $expected = array(
            'title' => '#10 news title',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'en', 11);
        $expected = array(
            'title' => '#11 news title',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'ru', 11);
        $expected = array(
            'title' => '#11 zagolovok',
            'text' => '#11 text novosti',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title'), 'news', 'ru', 11);
        $expected = array(
            'title' => '#11 zagolovok',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'ru', 12);
        $expected = array(
            'text' => '#12 text novosti',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'en', 12);
        $expected = array();
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title'), 'pages', 'en', 'main');
        $expected = array(
            'title' => 'Main page title',
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForItem(array('title'), 'pages', 'en', 'about');
        $expected = array();
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForList(array('title', 'text'), 'news', 'ru', array(8, 10, 11, 12));
        $expected = array(
            8 => array(),
            10 => array(),
            11 => array(
                'title' => '#11 zagolovok',
                'text' => '#11 text novosti',
            ),
            12 => array(
                'text' => '#12 text novosti',
            ),
        );
        $this->assertEquals($expected, $actual);

        $actual = $storage->getFieldsForList(array('title', 'text'), 'news', 'en', array(8, 10, 11, 12));
        $expected = array(
            8 => array(),
            10 => array(
                'title' => '#10 news title',
            ),
            11 => array(
                'title' => '#11 news title',
            ),
            12 => array(),
        );
        $this->assertEquals($expected, $actual);

        $fields = array(
            'title' => 'new title',
            'date' => 'new date',
        );
        $storage->setFields($fields, 'news', 'ru', 11);
        $actual = $storage->getFieldsForItem(array('title', 'text', 'date'), 'news', 'ru', 11);
        $expected = array(
            'title' => 'new title',
            'text' => '#11 text novosti',
            'date' => 'new date',
        );
        $this->assertEquals($expected, $actual);

        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals(7, $count);

        $storage->removeFields(array('date'), 'news', 'ru', 11);
        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals(6, $count);

        $storage->removeItem('news', 11);
        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals(3, $count);

        $storage->removeType('news');
        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals(1, $count);
    }
}
