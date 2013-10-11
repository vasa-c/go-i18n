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

    }

    /**
     * @covers go\I18n\Items\MultiType::removeAll
     */
    public function testRemoveAll()
    {

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

    }
}