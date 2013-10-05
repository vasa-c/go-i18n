<?php
/**
 * Test of magic fields classes
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\Tests\I18n\Helpers\mocks\MF;
use go\Tests\I18n\Helpers\mocks\MFD;

/**
 * @covers go\I18n\Helpers\MagicFields
 */
class MagicFieldsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\MagicFields::__get
     * @covers go\I18n\Helpers\MagicFields::__isset
     */
    public function testStaticFields()
    {
        $mf = new MF();
        $this->assertTrue(isset($mf->test_str));
        $this->assertTrue(isset($mf->test_cache));
        $this->assertFalse(isset($mf->unknown));
        $this->assertEquals('String', $mf->test_str);
        $testCache = $mf->test_cache;
        $this->assertEquals(1, $testCache->x);
        $this->assertSame($testCache, $mf->test_cache);
        $this->setExpectedException('go\I18n\Exceptions\FieldNotFound');
        return $mf->unknown;
    }

    /**
     * @covers go\I18n\Helpers\MagicFields::__get
     * @covers go\I18n\Helpers\MagicFields::__isset
     */
    public function testDynamicFields()
    {
        $mfd = new MFD();
        $this->assertTrue(isset($mfd->f_static));
        $this->assertTrue(isset($mfd->f_dynamic));
        $this->assertFalse(isset($mfd->unknown));
        $this->assertFalse(isset($mfd->udefined));
        $this->assertEquals('Static', $mfd->f_static);
        $this->assertEquals(2, $mfd->f_dynamic->y);
        $mfd->f_dynamic->y = 3;
        $this->assertEquals(3, $mfd->f_dynamic->y);
        $this->setExpectedException('go\I18n\Exceptions\FieldNotFound');
        return $mfd->unknown;
    }

    /**
     * @covers go\I18n\Helpers\MagicFields::__set
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testSetError()
    {
        $mf = new MF();
        $mf->field = 'value';
    }

    /**
     * @covers go\I18n\Helpers\MagicFields::__unset
     * @expectedException go\I18n\Exceptions\ReadOnly
     */
    public function testUnsetError()
    {
        $mf = new MF();
        unset($mf->field);
    }
}
