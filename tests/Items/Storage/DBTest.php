<?php
/**
 * Test of DB storage
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Items\Storage;

use \go\Tests\I18n\Items\mocks\TStorage;

/**
 * @covers go\I18n\Items\Storage\DB
 */
class DBTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testConfig = array(
        'table' => 'tbl',
        'testid' => '#id',
        'cols' => array(
            'type' => 't',
            'language' => 'lg',
        ),
    );

    /**
     * @param array $config [optional]
     * @return \go\Tests\I18n\Items\mocks\TStorage
     */
    private function create(array $config = null)
    {
        $config = $config ?: $this->testConfig;
        return new TStorage($config);
    }

    /**
     * @covers go\I18n\Items\Storage\DB::init
     */
    public function testInit()
    {
        $storage = $this->create();
        $this->assertEquals('#id', $storage->getTestId());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::getFieldsForItem
     */
    public function testGetFieldsForItem()
    {
        $storage = $this->create();
        $storage->getFieldsForItem(array('one', 'two'), 'c.type', 'ru', 10);
        $storage->getFieldsForItem(array('one'), 'c.type', 'en', '10');
        $expected = array(
            'SELECT field,value,value_big FROM tbl WHERE t=c.type AND lg=ru AND cid=10 AND field IN one,two',
            'SELECT field,value,value_big FROM tbl WHERE t=c.type AND lg=en AND cid_key=10 AND field=one',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::getFieldsForList
     */
    public function testGetFieldsForList()
    {
        $storage = $this->create();
        $cidsN = array('a' => 5, 'b' => 10, 'c' => 20);
        $cidsK = array('a' => 'x', 'b' => 'y', 'c' => 'z');
        $storage->getFieldsForList(array('x', 'y'), 'tp', 'ru', $cidsN);
        $storage->getFieldsForList(array('x', 'y'), 'tp', 'ru', $cidsK);
        $expected = array(
            'SELECT cid,field,value,value_big FROM tbl WHERE t=tp AND lg=ru AND cid IN 5,10,20 AND field IN x,y',
            'SELECT cid_key,field,value,value_big FROM tbl WHERE t=tp AND lg=ru AND cid_key IN x,y,z AND field IN x,y',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::removeItem
     */
    public function testRemoveItem()
    {
        $storage = $this->create();
        $storage->removeItem('tp', 20);
        $storage->removeItem('tp', 'key');
        $expected = array(
            'DELETE FROM tbl WHERE t=tp AND cid=20',
            'DELETE FROM tbl WHERE t=tp AND cid_key=key',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::removeLocalItem
     */
    public function testRemoveLocalItem()
    {
        $storage = $this->create();
        $storage->removeLocalItem('one', 'ru', 25);
        $storage->removeLocalItem('two', 'en', 'qwe');
        $expected = array(
            'DELETE FROM tbl WHERE t=one AND lg=ru AND cid=25',
            'DELETE FROM tbl WHERE t=two AND lg=en AND cid_key=qwe',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::removeFields
     */
    public function testRemoveFields()
    {
        $storage = $this->create();
        $storage->removeFields(array('x', 'y'), 'tp', 'ru', 10);
        $storage->removeFields(array('a', 'b'), 'tp', 'ru', '10');
        $expected = array(
            'DELETE FROM tbl WHERE t=tp AND lg=ru AND cid=10 AND field IN x,y',
            'DELETE FROM tbl WHERE t=tp AND lg=ru AND cid_key=10 AND field IN a,b',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::removeType
     */
    public function testRemoveType()
    {
        $storage = $this->create();
        $storage->removeType('tp');
        $expected = array(
            'DELETE FROM tbl WHERE t=tp',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    /**
     * @covers go\I18n\Items\Storage\DB::setFields
     */
    public function testSetFields()
    {
        $storage = $this->create();
        $fields = array(
            'f1' => 'val',
            'f2' => 'sval',
        );
        $storage->setFields($fields, 'one', 'ru', 10);
        $storage->setFields($fields, 'one', 'ru', 'qwe');
        $expected = array(
            'REPLACE INTO tbl (t,lg,cid,field,value,value_big) VALUES (one,ru,10,f1,val,NULL)',
            'REPLACE INTO tbl (t,lg,cid,field,value,value_big) VALUES (one,ru,10,f2,sval,NULL)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value,value_big) VALUES (one,ru,qwe,f1,val,NULL)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value,value_big) VALUES (one,ru,qwe,f2,sval,NULL)',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    public function testNoneType()
    {
        $config = $this->testConfig;
        $config['cols']['type'] = null;
        $storage = $this->create($config);
        $storage->getFieldsForItem(array('one', 'two'), 'c.type', 'ru', 10);
        $cidsN = array('a' => 5, 'b' => 10, 'c' => 20);
        $storage->getFieldsForList(array('x', 'y'), 'tp', 'ru', $cidsN);
        $storage->removeType('tp');
        $fields = array(
            'f1' => 'val',
            'f2' => 'sval',
        );
        $storage->setFields($fields, 'one', 'ru', 10);
        $expected = array(
            'SELECT field,value,value_big FROM tbl WHERE lg=ru AND cid=10 AND field IN one,two',
            'SELECT cid,field,value,value_big FROM tbl WHERE lg=ru AND cid IN 5,10,20 AND field IN x,y',
            'DELETE FROM tbl WHERE 1',
            'REPLACE INTO tbl (lg,cid,field,value,value_big) VALUES (ru,10,f1,val,NULL)',
            'REPLACE INTO tbl (lg,cid,field,value,value_big) VALUES (ru,10,f2,sval,NULL)',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    public function testNoneKey()
    {
        $config = $this->testConfig;
        $config['cols']['cid_key'] = null;
        $storage = $this->create($config);
        $storage->getFieldsForItem(array('one', 'two'), 'c.type', 'ru', 10);
        $storage->getFieldsForItem(array('one'), 'c.type', 'en', '10');
        $expected = array(
            'SELECT field,value,value_big FROM tbl WHERE t=c.type AND lg=ru AND cid=10 AND field IN one,two',
            'SELECT field,value,value_big FROM tbl WHERE t=c.type AND lg=en AND cid=10 AND field=one',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    public function testBiglen()
    {
        $config = $this->testConfig;
        $config['biglen'] = 5;
        $storage = $this->create($config);
        $fields = array(
            'f1' => 'val',
            'f2' => 'svalqwe',
        );
        $storage->setFields($fields, 'one', 'ru', 10);
        $storage->setFields($fields, 'one', 'ru', 'qwe');
        $expected = array(
            'REPLACE INTO tbl (t,lg,cid,field,value,value_big) VALUES (one,ru,10,f1,val,NULL)',
            'REPLACE INTO tbl (t,lg,cid,field,value,value_big) VALUES (one,ru,10,f2,NULL,svalqwe)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value,value_big) VALUES (one,ru,qwe,f1,val,NULL)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value,value_big) VALUES (one,ru,qwe,f2,NULL,svalqwe)',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    public function testNoneBig()
    {
        $config = $this->testConfig;
        $config['biglen'] = 5;
        $config['cols']['value_big'] = null;
        $storage = $this->create($config);
        $fields = array(
            'f1' => 'val',
            'f2' => 'svalqwe',
        );
        $storage->setFields($fields, 'one', 'ru', 10);
        $storage->setFields($fields, 'one', 'ru', 'qwe');
        $expected = array(
            'REPLACE INTO tbl (t,lg,cid,field,value) VALUES (one,ru,10,f1,val)',
            'REPLACE INTO tbl (t,lg,cid,field,value) VALUES (one,ru,10,f2,svalqwe)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value) VALUES (one,ru,qwe,f1,val)',
            'REPLACE INTO tbl (t,lg,cid_key,field,value) VALUES (one,ru,qwe,f2,svalqwe)',
        );
        $this->assertEquals($expected, $storage->getQueries());
    }

    public function testReadOnly()
    {
        $config = $this->testConfig;
        $config['readonly'] = true;
        $storage = $this->create($config);
        $storage->getFieldsForItem(array('x'), 'tp', 'ru', 1);
        $this->setExpectedException('go\I18n\Exceptions\StorageReadOnly');
        $storage->removeType('tp');
    }
}
