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

    public function testGetMulti()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.four');
        $mitem = $type->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $this->assertSame($mitem, $litem->getMulti());
    }

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

    public function testRemove()
    {

    }

    public function testClear()
    {

    }

    public function testAA()
    {

    }
}
