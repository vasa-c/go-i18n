<?php
/**
 * Test of items multi container
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items;

/**
 * @covers go\I18n\Items\LocalContainer
 */
class LocalContainerTest extends Base
{
    /**
     * @covers go\I18n\Items\LocalContainer::getKey
     */
    public function testGetKey()
    {
        $items = $this->create();
        $two = $items->one->two;
        $twoRu = $two->ru;
        $twoEn = $two->en;
        $this->assertEquals('one.two', $twoRu->getKey());
        $this->assertEquals('one.two', $twoEn->getKey());
    }

    /**
     * @covers go\I18n\Items\LocalContainer::getLanguage
     */
    public function testGetLanguage()
    {
        $items = $this->create();
        $two = $items->one->two;
        $twoRu = $two->ru;
        $twoEn = $two->en;
        $this->assertEquals('ru', $twoRu->getLanguage());
        $this->assertEquals('en', $twoEn->getLanguage());
    }

    /**
     * @covers go\I18n\Items\LocalContainer::getMulti
     */
    public function testGetMulti()
    {
        $items = $this->create();
        $this->assertSame($items, $items->ru->getMulti());
        $this->assertSame($items->one->two, $items->one->two->ru->getMulti());
        $this->assertSame($items->one->two, $items->one->two->en->getMulti());
    }

    /**
     * @covers go\I18n\Items\LocalContainer::getSubcontainer
     */
    public function testGetSubcontainer()
    {
        $items = $this->create();
        $ru = $items->getLocal('ru');
        $twoRu = $ru->getSubcontainer('one.two');
        $this->assertInstanceOf('go\I18n\Items\ILocalContainer', $twoRu);
        $this->assertSame($items->one->two, $twoRu->getMulti());
    }

    /**
     * @covers go\I18n\Items\LocalContainer::getType
     */
    public function testGetType()
    {
        $ru = $this->create()->getLocal('ru');
        $fourRu = $ru->getType('one.four');
        $this->assertInstanceOf('go\I18n\Items\ILocalType', $fourRu);
        $this->assertEquals('one.four', $fourRu->getKey());
    }

    /**
     * @covers go\I18n\Items\LocalContainer::existSubcontainer
     */
    public function testExistsSubcontainer()
    {
        $items = $this->create();
        $ru = $items->getLocal('ru');
        $this->assertTrue($ru->existsSubcontainer('one'));
        $this->assertTrue($ru->existsSubcontainer('one.two'));
        $this->assertFalse($ru->existsSubcontainer('one.two.three'));
        $this->assertFalse($ru->existsSubcontainer('two'));
    }

    /**
     * @covers go\I18n\Items\LocalContainer::__existsType
     */
    public function testExistsType()
    {
        $items = $this->create();
        $ru = $items->getLocal('ru');
        $this->assertFalse($ru->existsType('one'));
        $this->assertFalse($ru->existsType('one.two'));
        $this->assertTrue($ru->existsType('one.two.three'));
        $this->assertFalse($ru->existsType('two'));
    }

    /**
     * @covers go\I18n\Items\LocalContainer::__get
     */
    public function testMagicGet()
    {
        $items = $this->create();
        $oneRu = $items->getMultiSubcontainer('one')->getLocal('ru');
        $twoRu = $oneRu->two;
        $fourRu = $oneRu->four;
        $this->assertInstanceOf('go\I18n\Items\ILocalContainer', $twoRu);
        $this->assertInstanceOf('go\I18n\Items\ILocalType', $fourRu);
        $this->assertEquals('one.two', $twoRu->getKey());
        $this->assertEquals('one.four', $fourRu->getKey());
        $this->setExpectedException('go\I18n\Exceptions\ItemsChildNotFound');
        return $oneRu->unknown;
    }

    /**
     * @covers go\I18n\Items\LocalContainer::__isset
     */
    public function testMagicIsset()
    {
        $items = $this->create();
        $oneRu = $items->getMultiSubcontainer('one')->getLocal('ru');
        $this->assertTrue(isset($oneRu->two));
        $this->assertTrue(isset($oneRu->four));
        $this->assertFalse(isset($oneRu->unknown));
    }

    /**
     * @covers go\I18n\Items\LocalContainer::__set
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testMagicSet()
    {
        $oneRu = $this->create()->one->ru;
        $oneRu->x = 1;
    }

    /**
     * @covers go\I18n\Items\LocalContainer::__unset
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testMagicUnset()
    {
        $oneRu = $this->create()->one->ru;
        unset($oneRu->x);
    }
}
