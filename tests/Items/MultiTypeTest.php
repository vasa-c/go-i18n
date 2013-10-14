<?php
/**
 * Test of items multi type
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items;

/**
 * @covers go\I18n\Items\MultiType
 */
class MultiTypeTest extends Base
{
    /**
     * @covers go\I18n\Items\MultiType::getKey
     */
    public function testGetKey()
    {
        $items = $this->create();
        $three = $items->getMultiType('one.two.three');
        $four = $items->getMultiType('one.four');
        $invalid = $items->getMultiType('invalid');
        $this->assertEquals('one.two.three', $three->getKey());
        $this->assertEquals('one.four', $four->getKey());
        $this->assertEquals('invalid', $invalid->getKey());
    }

    /**
     * @covers go\I18n\Items\MultiType::getName
     */
    public function testGetName()
    {
        $items = $this->create();
        $three = $items->getMultiType('one.two.three');
        $four = $items->getMultiType('one.four');
        $invalid = $items->getMultiType('invalid');
        $this->assertEquals('threetype', $three->getName());
        $this->assertEquals('one.four', $four->getName());
        $this->assertEquals('invalid', $invalid->getName());
    }

    /**
     * @covers go\I18n\Items\MultiType::getLocal
     */
    public function testGetLocal()
    {
        $items = $this->create();
        $three = $items->getMultiType('one.two.three');
        $ru = $three->getLocal('ru');
        $this->assertInstanceOf('go\I18n\Items\ILocalType', $ru);
        $en = $three->getLocal('en');
        $this->assertSame($ru, $three->getLocal('ru'));
        $this->assertEquals('ru', $ru->getLanguage());
        $this->assertEquals('en', $en->getLanguage());
        $this->assertSame($three, $ru->getMulti());
    }

    /**
     * @covers go\I18n\Items\MultiType::getStorage
     */
    public function testGetStorage()
    {
        $items = $this->create();
        $three = $items->getMultiType('one.two.three');
        $four = $items->getMultiType('one.four');
        $invalid = $items->getMultiType('invalid');
        $storageThree = $three->getStorage();
        $this->assertInstanceOf('go\I18n\Items\Storage\IStorage', $storageThree);
        $this->assertEquals('#3', $storageThree->getTestId());
        $this->assertEquals($items->one->getStorage(), $four->getStorage());
        $this->setExpectedException('go\I18n\Exceptions\ConfigInvalid');
        return $invalid->getStorage();
    }

    /**
     * @covers go\I18n\Items\MultiType::getMultiItem
     */
    public function testGetMultiItem()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $item3 = $type->getMultiItem(3);
        $this->assertInstanceOf('go\I18n\Items\IMultiItem', $item3);
        $this->assertSame($item3, $type->getMultiItem(3));
        $this->assertNotSame($item3, $type->getMultiItem(4));
        $this->assertSame($type, $item3->getMultiType());
        $this->assertSame(3, $item3->getCID());
    }

    /**
     * @covers go\I18n\Items\MultiType::removeAll
     */
    public function testRemoveAll()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $type->removeAll();
        $storage = $type->getStorage();
        $expected = array(
            'DELETE FROM i18n_three WHERE type=threetype',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\MultiType::removeItem
     */
    public function testRemoveItem()
    {
        $items = $this->create();
        $type3 = $items->getMultiType('one.two.three');
        $type4 = $items->getMultiType('one.four');
        $type3->removeItem(3);
        $type4->removeItem(3);
        $storage3 = $type3->getStorage();
        $storage4 = $type4->getStorage();
        $expected3 = array(
            'DELETE FROM i18n_three WHERE type=threetype AND cid=3',
        );
        $this->assertEquals($expected3, $storage3->getQueries());
        $expected4 = array(
            'DELETE FROM i18n_one WHERE type=one.four AND cid_key=3',
        );
        $this->assertEquals($expected4, $storage4->getQueries());
    }

    /**
     * @covers go\I18n\Items\MultiType::__get
     */
    public function testMagicGet()
    {
        $four = $this->create()->getMultiType('one.four');
        $this->assertSame($four->getLocal('ru'), $four->ru);
        $this->assertSame($four->getLocal('en'), $four->en);
        $this->setExpectedException('\go\I18n\Exceptions\LanguageNotExists');
        return $four->unknown;
    }

    /**
     * @covers go\I18n\Items\MultiType::__isset
     */
    public function testMagicIsset()
    {
        $four = $this->create()->getMultiType('one.four');
        $this->assertTrue(isset($four->ru));
        $this->assertTrue(isset($four->en));
        $this->assertFalse(isset($four->unknown));
    }

    /**
     * @covers go\I18n\Items\MultiType::__set
     * @expectedException \go\I18n\Exceptions\ReadOnly
     */
    public function testMagicSet()
    {
        $four = $this->create()->getMultiType('one.four');
        $four->ru = 1;
    }

    /**
     * @covers go\I18n\Items\MultiType::__unset
     * @expectedException \go\I18n\Exceptions\ReadOnly
     */
    public function testMagicUnset()
    {
        $four = $this->create()->getMultiType('one.four');
        unset($four->ru);
    }

    /**
     * @covers go\I18n\Items\MultiType::offsetGet
     */
    public function testAAGet()
    {
        $items = $this->create();
        $type = $items->getMultiType('one.two.three');
        $this->assertSame($type->getMultiItem(3), $type[3]);
    }

    /**
     * @covers go\I18n\Items\MultiType::offsetExists
     */
    public function testAAIsset()
    {
        $four = $this->create()->getMultiType('one.four');
        $this->assertTrue(isset($four[10]));
        $this->assertTrue(isset($four[1230]));
        $this->assertTrue(isset($four['key']));
    }

    /**
     * @covers go\I18n\Items\MultiType::offsetSet
     * @expectedException \go\I18n\Exceptions\ReadOnly
     */
    public function testAASet()
    {
        $four = $this->create()->getMultiType('one.four');
        $four[10] = 1;
    }

    /**
     * @covers go\I18n\Items\MultiType::offsetUnset
     */
    public function testAAUnset()
    {
        $items = $this->create();
        $type3 = $items->getMultiType('one.two.three');
        unset($type3[5]);
        $storage3 = $type3->getStorage();
        $expected3 = array(
            'DELETE FROM i18n_three WHERE type=threetype AND cid=5',
        );
        $this->assertEquals($expected3, $storage3->getQueries());
    }
}
