<?php
/**
 * Test of the creator helper
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\I18n\Helpers\Creator;

/**
 * @covers go\I18n\Helpers\Creator
 */
class CreatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\Creator::create
     */
    public function testCreateObject()
    {
        $base = 'go\Tests\I18n\Helpers\mocks\ICreated';
        $params = new mocks\Created();
        $this->assertSame($params, Creator::create($params));
        $this->assertSame($params, Creator::create($params, array('base' => $base)));
        $paramsStd = (object)array();
        $this->assertSame($paramsStd, Creator::create($paramsStd));
        $this->setExpectedException('go\I18n\Exceptions\ConfigService');
        Creator::create($paramsStd, array('base' => $base));
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     */
    public function testCreateClassname()
    {
        $base = 'go\Tests\I18n\Helpers\mocks\ICreated';
        $params = 'go\Tests\I18n\Helpers\mocks\Created';
        $actual = Creator::create($params);
        $this->assertInstanceOf($params, $actual);
        $this->assertEquals(array(), $actual->getParams());
        $this->assertInstanceOf($params, Creator::create($actual, array('base' => $base)));
        $nclassname = 'go\Tests\I18n\Helpers\mocks\MF';
        $this->assertInstanceOf($nclassname, Creator::create($nclassname));
        $this->setExpectedException('go\I18n\Exceptions\ConfigService');
        Creator::create($nclassname, array('base' => $base));
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     */
    public function testCreateClassnameNull()
    {
        $default = 'go\Tests\I18n\Helpers\mocks\Created';
        $this->assertInstanceOf($default, Creator::create(null, array('default' => $default)));
        $this->assertInstanceOf($default, Creator::create(true, array('default' => $default)));
        $this->setExpectedException('go\I18n\Exceptions\ConfigService');
        Creator::create(null);
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     */
    public function testCreateParams()
    {
        $classname = 'go\Tests\I18n\Helpers\mocks\Created';
        $params1 = array(
            'classname' => $classname,
            'param' => 'value',
        );
        $actual1 = Creator::create($params1);
        $this->assertInstanceOf($classname, $actual1);
        $this->assertEquals($params1, $actual1->getParams());
        $params2 = array(
            'param' => 'value',
        );
        $actual2 = Creator::create($params2, array('default' => $classname));
        $this->assertInstanceOf($classname, $actual2);
        $this->assertEquals($params2, $actual2->getParams());
        $this->setExpectedException('go\I18n\Exceptions\ConfigService');
        Creator::create($params2);
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     * @expectedException go\I18n\Exceptions\ServiceDisabled
     */
    public function testCreateFalse()
    {
        Creator::create(false, array('default' => 'go\Tests\I18n\Helpers\mocks\Created'));
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     * @expectedException go\I18n\Exceptions\ConfigService
     */
    public function testCreateInvalidValue()
    {
        Creator::create(123);
    }

    /**
     * @covers go\I18n\Helpers\Creator::create
     * @expectedException go\I18n\Exceptions\ConfigService
     */
    public function testClassNotFound()
    {
        $classname = 'go\Tests\I18n\Helpers\mocks\Undefined';
        Creator::create($classname);
    }

    public function testNS()
    {
        $classname = 'Created';
        $instance = Creator::create($classname, array('ns' => 'go\Tests\I18n\Helpers\mocks'));
        $this->assertInstanceOf('go\Tests\I18n\Helpers\mocks\Created', $instance);

        $classname = '\go\Tests\I18n\Helpers\mocks\Created';
        $instance = Creator::create($classname, array('ns' => 'go\Tests\I18n\Helpers\mocks'));
        $this->assertInstanceOf('go\Tests\I18n\Helpers\mocks\Created', $instance);

        $this->setExpectedException('go\I18n\Exceptions\ConfigService');
        $classname = 'go\Tests\I18n\Helpers\mocks\Created';
        return Creator::create($classname, array('ns' => 'go\Tests\I18n\Helpers\mocks'));
    }
}
