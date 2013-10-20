<?php
/**
 * Test of Modules helper for UI
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\UI\Helpers;

use go\I18n\UI\Helpers\Modules;

/**
 * @covers go\I18n\UI\Helpers\Modules
 */
class ModulesTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $params = array(
            'modules' => array(
                'one' => 'one-dir',
                'two' => 'two-dir',
            ),
        );
        $modules = new Modules($params);
        $this->assertTrue($modules->exists('one'));
        $this->assertTrue($modules->exists('two'));
        $this->assertFalse($modules->exists('three'));
        $this->assertEquals('one-dir', $modules->getDir('one'));
        $this->assertEquals('two-dir', $modules->getDir('two'));
        $this->assertNull($modules->getDir('three'));
        $this->assertEquals(array('one', 'two'), $modules->getListModules());
    }

    public function testGetModule()
    {
        $params = array(
            'get_modules' => function () {
                return array(
                    'one' => 'one-g-dir',
                    'two' => 'two-g-dir',
                );
            }
        );
        $modules = new Modules($params);
        $this->assertTrue($modules->exists('one'));
        $this->assertTrue($modules->exists('two'));
        $this->assertFalse($modules->exists('three'));
        $this->assertEquals('one-g-dir', $modules->getDir('one'));
        $this->assertEquals('two-g-dir', $modules->getDir('two'));
        $this->assertNull($modules->getDir('three'));
        $this->assertEquals(array('one', 'two'), $modules->getListModules());
    }

    /**
     * @expectedException go\I18n\Exceptions\ConfigInvalid
     */
    public function testErrorModule()
    {
        $params = array();
        $modules = new Modules($params);
        $modules->exists('one');
    }

    public function testPatternDir()
    {
        $params = array(
            'modules' => array(
                'one' => 'one-dir',
                'two' => true,
            ),
            'pattern_dir' => '/dir/{{ module }}/ui',
        );
        $modules = new Modules($params);
        $this->assertTrue($modules->exists('one'));
        $this->assertTrue($modules->exists('two'));
        $this->assertFalse($modules->exists('three'));
        $this->assertEquals('one-dir', $modules->getDir('one'));
        $this->assertEquals('/dir/two/ui', $modules->getDir('two'));
        $this->assertNull($modules->getDir('three'));
    }

    public function testGetDir()
    {
        $params = array(
            'modules' => array(
                'one' => 'one-dir',
                'two' => true,
            ),
            'get_dir' => function ($module) {
                return '/dir/'.$module;
            },
        );
        $modules = new Modules($params);
        $this->assertTrue($modules->exists('one'));
        $this->assertTrue($modules->exists('two'));
        $this->assertFalse($modules->exists('three'));
        $this->assertEquals('one-dir', $modules->getDir('one'));
        $this->assertEquals('/dir/two', $modules->getDir('two'));
        $this->assertNull($modules->getDir('three'));
    }

    public function testErrorDir()
    {
        $params = array(
            'modules' => array(
                'one' => 'one-dir',
                'two' => true,
            ),
        );
        $modules = new Modules($params);
        $this->assertTrue($modules->exists('one'));
        $this->assertTrue($modules->exists('two'));
        $this->assertFalse($modules->exists('three'));
        $this->assertEquals('one-dir', $modules->getDir('one'));
        $this->assertNull($modules->getDir('three'));
        $this->setExpectedException('go\I18n\Exceptions\ConfigInvalid');
        $this->assertEquals('/dir/two', $modules->getDir('two'));
    }
}
