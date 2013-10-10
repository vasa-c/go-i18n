<?php
/**
 * Test of items multi container
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n;

/**
 * @covers go\I18n\Items\MultiContainer
 */
class MultiContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testConfig = array(
        'containers' => array(
            'one' => array(
                'containers' => array(
                    'two' => array(
                        'types' => array(
                            'three' => array(
                                'type' => 'type.three',
                                'fields' => array(),
                            ),
                        ),
                    ),
                ),
                'types' => array(
                    'four' => array(
                        'fields' => array(),
                    ),
                ),
            ),
        ),
    );

    /**
     * @param array $itemsConfig [optional]
     * @return \go\I18n\Items\IMultiContainer
     */
    private function create($itemsConfig = null)
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => true,
            ),
            'default' => 'en',
            'items' => $itemsConfig ?: $this->testConfig,
        );
        $i18n = new \go\I18n\I18n($config);
        return $i18n->items;
    }

    /**
     * @covers go\I18n\Items\MultiContainer::existsSubcontainer
     */
    public function testExistsSubcontainer()
    {
        $items = $this->create();
        $this->assertTrue($items->existsSubcontainer('one'));
        $this->assertTrue($items->existsSubcontainer('one.two'));
        $this->assertFalse($items->existsSubcontainer('one.two.three'));
        $this->assertFalse($items->existsSubcontainer('two'));
    }

    /**
     * @covers go\I18n\Items\MultiContainer::existsType
     */
    public function testExistsType()
    {
        $items = $this->create();
        $this->assertTrue($items->existsType('one.two.three'));
        $this->assertTrue($items->existsType('one.four'));
        $this->assertFalse($items->existsType('one.two.unknown'));
        $this->assertFalse($items->existsType('one.two'));
        $this->assertFalse($items->existsType('one.s.s'));
    }

    /**
     * @covers go\I18n\Items\MultiContainer::getMultiSubcontainer
     */
    public function testGetMultiSubcontainer()
    {
        $items = $this->create();
        $one = $items->getMultiSubcontainer('one');
        $this->assertInstanceOf('go\I18n\Items\IMultiContainer', $one);
        $two = $items->getMultiSubcontainer('one.two');
        $this->assertSame($two, $one->getMultiSubcontainer('two'));
        $this->setExpectedException('\go\I18n\Exceptions\ItemsChildNotFound');
        return $items->getMultiSubcontainer('one.two.three');
    }

    /**
     * @covers go\I18n\Items\MultiContainer::getMultiType
     */
    public function testGetMultiType()
    {
        $items = $this->create();
        $three = $items->getMultiType('one.two.three');
        $this->assertInstanceOf('go\I18n\Items\IMultiType', $three);
        $this->assertSame($three, $items->getMultiSubcontainer('one')->getMultiType('two.three'));
        $this->setExpectedException('\go\I18n\Exceptions\ItemsChildNotFound');
        return $items->getMultiType('one.two.four');
    }

    /**
     * @covers go\I18n\Items\MultiContainer::getLocal
     */
    public function testGetLocal()
    {
        $items = $this->create();
        $ru = $items->getLocal('ru');
        $this->assertInstanceOf('go\I18n\Items\ILocalContainer', $ru);
        $this->assertSame($ru, $items->getLocal('ru'));
        $this->assertEquals('ru', $ru->getLanguage());
        $this->assertSame($items, $ru->getMulti());
        $this->setExpectedException('\go\I18n\Exceptions\LanguageNotExists');
        return $items->getLocal('jp');
    }

    /**
     * @covers go\I18n\Items\MultiContainer::getKey
     */
    public function testGetKey()
    {
        $items = $this->create();
        $one = $items->getMultiSubcontainer('one');
        $two = $one->getMultiSubcontainer('two');
        $this->assertEquals('', $items->getKey());
        $this->assertEquals('one', $one->getKey());
        $this->assertEquals('one.two', $two->getKey());
    }

    /**
     * @covers go\I18n\Items\MultiContainer::getStorage
     */
    public function testGetStorage()
    {

    }

    /**
     * @covers go\I18n\Items\MultiContainer::__isset
     */
    public function testMagicIsset()
    {
        $items = $this->create();
        $one = $items->getMultiSubcontainer('one');
        $this->assertTrue(isset($items->ru));
        $this->assertTrue(isset($items->one));
        $this->assertTrue(isset($one->two));
        $this->assertTrue(isset($one->four));
        $this->assertFalse(isset($items->jp));
        $this->assertFalse(isset($items->two));
    }

    /**
     * @covers go\I18n\Items\MultiContainer::__get
     */
    public function testMagicGet()
    {
        $items = $this->create();
        $this->assertSame($items->getLocal('ru'), $items->ru);
        $this->assertSame($items->getMultiSubcontainer('one.two'), $items->one->two);
        $this->assertSame($items->getMultiType('one.four'), $items->one->four);

        $this->setExpectedException('\go\I18n\Exceptions\ItemsChildNotFound');
        return $items->unknown;
    }

    /**
     * @covers go\I18n\Items\MultiContainer::__set
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testMagicSet()
    {
        $items = $this->create();
        $items->ru = 1;
    }

    /**
     * @covers go\I18n\Items\MultiContainer::__unset
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testMagicUnset()
    {
        $items = $this->create();
        unset($items->ru);
    }
}
