<?php
/**
 * Test of items local type
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items;

/**
 * @covers go\I18n\Items\LocalType
 */
class LocalTypeTest extends Base
{
    /**
     * @covers go\I18n\Items\LocalType::getKey
     * @covers go\I18n\Items\LocalType::getName
     * @covers go\I18n\Items\LocalType::getLanguage
     * @covers go\I18n\Items\LocalType::getMulti
     */
    public function testGet()
    {
        $items = $this->create();
        $mtype = $items->getMultiType('one.two.three');
        $ltype = $mtype->getLocal('ru');
        $this->assertEquals('one.two.three', $ltype->getKey());
        $this->assertEquals('threetype', $ltype->getName());
        $this->assertEquals('ru', $ltype->getLanguage());
        $this->assertEquals($mtype, $ltype->getMulti());
    }

    /**
     * @covers go\I18n\Items\LocalType::getItem
     */
    public function testGetItem()
    {
        $items = $this->create();
        $mtype = $items->getMultiType('one.two.three');
        $ltype = $mtype->getLocal('ru');
        $item3 = $mtype->getMultiItem(3)->getLocal('ru');
        $item4 = $ltype->getItem(4);
        $fields = array(
            'title' => 'xzccdfg',
        );
        $this->assertSame($item4, $mtype->getMultiItem(4)->getLocal('ru'));
        $this->assertSame($item3, $ltype->getItem(3, $fields));
        $this->assertEquals($fields, $item3->getLoadedFields());
    }

    /**
     * @covers go\I18n\Items\LocalType::getListItems
     */
    public function testGetListItems()
    {

    }

    /**
     * @covers go\I18n\Items\LocalType::fillArray
     */
    public function testFillArray()
    {

    }

    /**
     * @covers go\I18n\Items\LocalType::removeItem
     */
    public function testRemoveItem()
    {

    }
}
