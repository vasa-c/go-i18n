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
     * @var array
     */
    private $sqllogs = array();

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
        $config['types']['real']['storage']['logger'] = array($this, 'sqllog');
        $items = $this->create($config);
        return $items->getMultiType('real');
    }

    /**
     * @param string $sql
     */
    public function sqllog($sql)
    {
echo 'SQL: '.$sql.\PHP_EOL;
        $this->sqllogs[] = $sql;
    }

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
        $mtype = $this->createReal();
        $ltype = $mtype->getLocal('ru');

        $item10 = $ltype->getItem(10);
        $item11 = $ltype->getItem(11);

        $list = $ltype->getListItems(array(10, 12, 11));
        $this->assertTrue(isset($list[12]));
        $item12 = $list[12];
        $this->assertInstanceOf('go\I18n\Items\ILocalItem', $item12);
        $expected = array(
            10 => $item10,
            11 => $item11,
            12 => $item12,
        );
        $this->assertEquals($expected, $list);
        $this->assertEmpty($this->sqllogs);

        $item10->knownValuesSet(array('title' => 't 10', 'fulltext' => 'ft 10'));
        $item11->knownValuesSet(array('title' => 't 11'));

        $list = $ltype->getListItems(array(10, 11), array('title'));
        $expected = array(
            10 => $item10,
            11 => $item11,
        );
        $this->assertEquals($expected, $list);
        $this->assertEmpty($this->sqllogs);

        $list = $ltype->getListItems(array(10, 11), array('title', 'fulltext'));
        $expected = array(
            10 => $item10,
            11 => $item11,
        );
        $this->assertEquals($expected, $list);
        $expectedLoaded = array(
            'title' => 't 11',
            'fulltext' => '#11 text novosti',
        );
        $this->assertEquals($expectedLoaded, $item11->getLoadedFields());
        $expectedSql = array(
            'SELECT "cid","value","value"',
        );
        $this->assertEmpty($this->sqllogs, $expectedSql);
        $this->sqllogs = array();
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
