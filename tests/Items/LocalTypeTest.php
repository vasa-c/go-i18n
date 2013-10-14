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
}
