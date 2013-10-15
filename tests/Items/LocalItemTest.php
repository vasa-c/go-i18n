<?php
/**
 * Test of local item
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items;

/**
 * @covers go\I18n\Items\LocalItem
 */
class LocalItemTest extends Base
{
    /**
     * @return \go\I18n\Items\IMultiType
     */
    private function createReal()
    {
        if (!\extension_loaded('sqlite3')) {
            $this->markTestSkipped('The php-extension sqlite3 is not loaded');
        }
        $db = new \SQLite3(':memory:');
        $dump = \file_get_contents(__DIR__.'/Storage/sqlite-install.sql');
        $db->query($dump);
        $config = $this->getTestConfig();
        $config['types']['real']['storage']['db'] = $db;
        $items = $this->create($config);
        return $items->getMultiType('real');
    }

    /**
     * @covers go\I18n\Items\LocalItem::getMulti
     */
    public function testGetMulti()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.four');
        $mitem = $type->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $this->assertSame($mitem, $litem->getMulti());
    }

    /**
     * @covers go\I18n\Items\LocalItem::getAnotherLanguage
     */
    public function testGetAnotherLanguage()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.four');
        $mitem = $type->getMultiItem(3);
        $litemRu = $mitem->getLocal('ru');
        $litemEn = $litemRu->getAnotherLanguage('en');
        $this->assertSame($mitem->getLocal('en'), $litemEn);
        $this->assertSame($litemRu, $litemRu->getAnotherLanguage('ru'));
        $this->assertSame($litemRu, $litemEn->getAnotherLanguage('ru'));
    }

    /**
     * @covers go\I18n\Items\LocalItem::getType
     */
    public function testGetType()
    {
        $items = $this->create();
        $mtype = $items->getMultiType('one.four');
        $mitem = $mtype->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $ltype = $litem->getType();
        $this->assertInstanceOf('go\I18n\Items\ILocalType', $ltype);
        $this->assertSame($mtype, $ltype->getMulti());
    }

    public function testGetCID()
    {
        $items = $this->create();
        $mtype = $items->getMultiType('one.four');
        $mitem = $mtype->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $this->assertSame('3', $litem->getCID());
    }

    /**
     * @covers go\I18n\Items\LocalItem::__get
     */
    public function testMagicGet()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');

        $item11Ru = $ltypeRu->getItem(11);
        $this->assertEmpty($item11Ru->getLoadedFields());
        $this->assertEquals($item11Ru->title, '#11 zagolovok');
        $this->assertEquals($item11Ru->fulltext, '#11 text novosti');
        $expected = array(
            'title' => '#11 zagolovok',
            'fulltext' => '#11 text novosti',
        );
        $this->assertEquals($expected, $item11Ru->getLoadedFields());
        $this->assertEquals($item11Ru->description, '');

        $item11En = $item11Ru->getAnotherLanguage('en');
        $this->assertEquals($item11En->title, '#11 news title');
        $this->assertEquals($item11En->fulltext, '');
        $this->assertEquals($item11En->description, '');

        $this->setExpectedException('go\I18n\Exceptions\ItemsFieldNotExists');
        return $item11Ru->unknown;
    }

    /**
     * @covers go\I18n\Items\LocalItem::__isset
     */
    public function testMagicIsset()
    {
        $items = $this->create();
        $mtype = $items->getMultiType('one.two.three');
        $mitem = $mtype->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $this->assertTrue(isset($litem->title));
        $this->assertTrue(isset($litem->text));
        $this->assertTrue(isset($litem->description));
        $this->assertFalse(isset($litem->d));
        $this->assertFalse(isset($litem->unknown));
    }

    /**
     * @covers go\I18n\Items\LocalItem::getListFields
     */
    public function testGetListFields($fields = true)
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);

        $this->assertEmpty($item11Ru->getLoadedFields());
        $result = $item11Ru->getListFields(array('title', 'description'));
        $expected = array(
            'title' => '#11 zagolovok',
            'description' => '',
        );
        $this->assertEquals($expected, $result);
        $this->assertEquals($expected, $item11Ru->getLoadedFields());

        $result = $item11Ru->getListFields();
        $expected = array(
            'title' => '#11 zagolovok',
            'fulltext' => '#11 text novosti',
            'description' => '',
        );
        $this->assertEquals($expected, $result);
        $this->assertEquals($expected, $item11Ru->getLoadedFields());
        $this->assertEquals($expected, $item11Ru->getListFields());

        $this->setExpectedException('go\I18n\Exceptions\ItemsFieldNotExists');
        $item11Ru->getListFields(array('title', 'unknown'));
    }

    /**
     * @covers go\I18n\Items\LocalItem::__set
     */
    public function testMagicSet()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);
        $item11Ru->title = 'new title';
        $this->assertEquals('new title', $item11Ru->title);
        $item11Ru->resetCache();
        $this->assertEquals('#11 zagolovok', $item11Ru->title);
        $item11Ru->title = 'new title';
        $item11Ru->save();
        $item11Ru->resetCache();
        $this->assertEquals('new title', $item11Ru->title);
    }

    /**
     * @covers go\I18n\Items\LocalItem::setListFields
     */
    public function testSetListFields()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);

        $newfields = array(
            'title' => 'new title',
            'description' => 'new desc',
        );
        $item11Ru->setListFields($newfields);
        $this->assertEquals('new title', $item11Ru->title);
        $this->assertEquals('#11 text novosti', $item11Ru->fulltext);
        $expected = array(
            'title' => 'new title',
            'fulltext' => '#11 text novosti',
            'description' => 'new desc',
        );
        $this->assertEquals($expected, $item11Ru->getLoadedFields());

        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);
        $this->assertEmpty($item11Ru->getLoadedFields());
        $expected = array(
            'title' => '#11 zagolovok',
            'fulltext' => '#11 text novosti',
            'description' => '',
        );
        $this->assertEquals($expected, $item11Ru->getListFields());
    }

    /**
     * @covers go\I18n\Items\LocalItem::remove
     */
    public function testRemove()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);
        $item11En = $mtype->getLocal('en')->getItem(11);

        $config = $mtype->getConfig();
        $db = $config['storage']['db'];
        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals('#11 zagolovok', $item11Ru->title);
        $this->assertEquals('#11 news title', $item11En->title);
        $item11Ru->remove();
        $this->assertEmpty($item11Ru->title);
        $this->assertEmpty($item11En->title);
        $item11Ru->resetCache();
        $item11En->resetCache();
        $this->assertEmpty($item11Ru->title);
        $this->assertEmpty($item11En->title);
        $count2 = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count2 = $count2[0];
        $this->assertEquals($count - 3, $count2);
    }

    /**
     * @covers go\I18n\Items\LocalItem::clear
     */
    public function testClear()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);
        $item11En = $mtype->getLocal('en')->getItem(11);

        $config = $mtype->getConfig();
        $db = $config['storage']['db'];
        $count = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count = $count[0];
        $this->assertEquals('#11 zagolovok', $item11Ru->title);
        $this->assertEquals('#11 news title', $item11En->title);
        $item11Ru->clear();
        $this->assertEmpty($item11Ru->title);
        $this->assertEquals('#11 news title', $item11En->title);
        $item11Ru->resetCache();
        $item11En->resetCache();
        $this->assertEmpty($item11Ru->title);
        $this->assertEquals('#11 news title', $item11En->title);
        $count2 = $db->query('SELECT COUNT(1) FROM `items`')->fetchArray(\SQLITE3_NUM);
        $count2 = $count2[0];
        $this->assertEquals($count - 2, $count2);
    }

    /**
     * @covers go\I18n\Items\LocalItem::knownValuesSet
     */
    public function testKnownValuesSet()
    {
        $mtype = $this->createReal();
        $ltypeRu = $mtype->getLocal('ru');
        $item11Ru = $ltypeRu->getItem(11);

        $this->assertEquals('#11 zagolovok', $item11Ru->title);
        $item11Ru->fulltext = 'new text';
        $expected = array(
            'title' => '#11 zagolovok',
            'fulltext' => 'new text',
        );
        $this->assertEquals($expected, $item11Ru->getLoadedFields());

        $known = array(
            'title' => 'qwe',
            'fulltext' => 'rty',
            'description' => 'iop',
        );
        $item11Ru->knownValuesSet($known);

        $expected = array(
            'title' => 'qwe',
            'fulltext' => 'new text',
            'description' => 'iop',
        );
        $this->assertEquals($expected, $item11Ru->getLoadedFields());

        $item11Ru->save();
        $item11Ru->resetCache();
        $expected = array(
            'title' => '#11 zagolovok',
            'fulltext' => 'new text',
            'description' => '',
        );
        $this->assertEquals($expected, $item11Ru->getListFields());
    }
}
