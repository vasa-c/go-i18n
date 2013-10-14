<?php
/**
 * Test of multi item
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items;

/**
 * @covers go\I18n\Items\MultiItem
 */
class MultiItemTest extends Base
{
    /**
     * @covers go\I18n\Items\MultiItem::getMultiType
     */
    public function testGetMultiType()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $item = $type->getMultiItem(16);
        $this->assertEquals($type, $item->getMultiType());
    }

    /**
     * @covers go\I18n\Items\MultiItem::getCID
     */
    public function testGetCID()
    {
        $items = $this->create();
        $type3 = $items->getMultiType('one.two.three');
        $type4 = $items->getMultiType('one.four');
        $item3 = $type3->getMultiItem(3);
        $item4 = $type4->getMultiItem(4);
        $this->assertSame(3, $item3->getCID());
        $this->assertSame('4', $item4->getCID());
    }

    /**
     * @covers go\I18n\Items\MultiItem::getLocal
     */
    public function testGetLocal()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $mitem = $type->getMultiItem(3);
        $litem = $mitem->getLocal('ru');
        $this->assertInstanceOf('go\I18n\Items\ILocalItem', $litem);
        $this->assertEquals($mitem, $litem->getMulti());
        $this->setExpectedException('go\I18n\Exceptions\LanguageNotExists');
        $mitem->getLocal('qw');
    }

    /**
     * @covers go\I18n\Items\MultiItem::remove
     */
    public function testRemove()
    {
        $items = $this->create();
        $type3 = $items->getMultiType('one.two.three');
        $type4 = $items->getMultiType('one.four');
        $item3 = $type3->getMultiItem(3);
        $item4 = $type4->getMultiItem(4);
        $item3->remove();
        $item4->remove();
        $storage3 = $type3->getStorage();
        $storage4 = $type4->getStorage();
        $expected3 = array(
            'DELETE FROM i18n_three WHERE type=threetype AND cid=3',
        );
        $expected4 = array(
            'DELETE FROM i18n_one WHERE type=one.four AND cid_key=4',
        );
        $this->assertEquals($expected3, $storage3->getQueries());
        $this->assertEquals($expected4, $storage4->getQueries());
    }

    /**
     * @covers go\I18n\Items\MultiItem::__get
     */
    public function testMagicGet()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $mitem = $type->getMultiItem(5);
        $this->assertSame($mitem->getLocal('ru'), $mitem->ru);
        $this->setExpectedException('go\I18n\Exceptions\LanguageNotExists');
        return $mitem->qw;
    }

    /**
     * @covers go\I18n\Items\MultiItem::__isset
     */
    public function testMagicIsset()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $mitem = $type->getMultiItem(6);
        $this->assertTrue(isset($mitem->ru));
        $this->assertFalse(isset($mitem->qw));
    }
}
