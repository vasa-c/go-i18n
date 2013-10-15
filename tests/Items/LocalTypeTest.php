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
            'title' => '',
            'fulltext' => '#11 text novosti',
        );
        $this->assertEquals($expectedLoaded, $item11->getLoadedFields());
        $sql = 'SELECT "cid","field","value","value_big" FROM "items"';
        $sql .= ' WHERE "type"=\'news\' AND "lg"=\'ru\' AND "cid" IN (\'11\') AND "field"=\'text\'';
        $expectedSql = array(
            $sql,
        );
        $this->assertEquals($this->sqllogs, $expectedSql);
        $this->sqllogs = array();

        $list = $ltype->getListItems(array(10, 11), array('title', 'fulltext'));
        $expected = array(
            10 => $item10,
            11 => $item11,
        );
        $this->assertEquals($expected, $list);
        $this->assertEmpty($this->sqllogs);

        $list = $ltype->getListItems(array(10, 11, 12), array('title', 'fulltext'));
        $expected = array(
            10 => $item10,
            11 => $item11,
            12 => $item12,
        );
        $this->assertEquals($expected, $list);
        $sql = 'SELECT "cid","field","value","value_big" FROM "items"';
        $sql .= ' WHERE "type"=\'news\' AND "lg"=\'ru\' AND "cid" IN (\'12\') AND "field" IN (\'title\',\'text\')';
        $expectedSql = array(
            $sql,
        );
        $this->assertEquals($this->sqllogs, $expectedSql);

        $this->setExpectedException('go\I18n\Exceptions\ItemsFieldNotExists');
        $ltype->getListItems(array(10, 11, 12), array('title', 'fulltext', 'unknown'));
    }

    /**
     * @covers go\I18n\Items\LocalType::fillArray
     */
    public function testFillArray()
    {
        $mtype = $this->createReal();
        $ltype = $mtype->getLocal('ru');

        $items = array(
            '10' => array(
                'id' => 11,
                'tag' => 'qw',
            ),
            '11' => array(
                'id' => 10,
                'tag' => 'ss',
            ),
            '12' => array(
                'id' => 12,
                'tag' => 'er',
            ),
        );

        $ltype->getListItems(array(11), array('description'));
        $actual = $ltype->fillArray($items, array('title', 'fulltext'), null, 'f');
        $expected = array(
            '10' => array(
                'id' => 11,
                'tag' => 'qw',
                'f' => array(
                    'title' => '',
                    'fulltext' => '',
                ),
            ),
            '11' => array(
                'id' => 10,
                'tag' => 'ss',
                'f' => array(
                    'title' => '#11 zagolovok',
                    'fulltext' => '#11 text novosti',
                ),
            ),
            '12' => array(
                'id' => 12,
                'tag' => 'er',
                'f' => array(
                    'title' => '',
                    'fulltext' => '#12 text novosti',
                ),
            ),
        );
        $this->assertEquals($expected, $actual);

        $actual = $ltype->fillArray($items, array('title', 'fulltext'), 'id');
        $expected = array(
            '10' => array(
                'id' => 11,
                'tag' => 'qw',
                'title' => '#11 zagolovok',
                'fulltext' => '#11 text novosti'
            ),
            '11' => array(
                'id' => 10,
                'tag' => 'ss',
                'title' => '',
                'fulltext' => '',
            ),
            '12' => array(
                'id' => 12,
                'tag' => 'er',
                'title' => '',
                'fulltext' => '#12 text novosti',
             ),
        );
        $this->assertEquals($expected, $actual);

        $this->setExpectedException('go\I18n\Exceptions\ItemsFieldNotExists');
        $ltype->fillArray($items, array('unk'), 'id');
    }
}
