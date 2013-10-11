<?php
/**
 * Test of UI
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\UI;

/**
 * @covers go\I18n\UI
 */
class UITest extends \PHPUnit_Framework_TestCase
{
    public function testUI()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'ru' => 'en',
                'by' => 'ru',
            ),
            'default' => 'en',
            'ui' => array(
                'dirname' => __DIR__.'/testui',
            ),
        );
        $i18n = new \go\I18n\I18n($config);
        $ui = $i18n->ui;
        $by = $ui->by;
        $this->assertEquals(array('раніцы', 'вечара'), $by->calendar->ampm);
        $this->assertEquals(array('Янв', 'Фев', 'Мар', 'Апр'), $by->calendar->months);
        $this->assertEquals(array('Jan', 'Feb', 'Mar', 'Apr'), $ui->en->calendar->months);
        $this->assertEquals(array('M', 'T', 'W', 'T', 'F', 'S', 'S'), $by->calendar->days);
        $this->assertEquals(array('M', 'T', 'W', 'T', 'F', 'S', 'S'), $by->get('calendar.days'));
        $this->assertEquals(array('M', 'T', 'W', 'T', 'F', 'S', 'S'), $by['calendar']['days']);
        $this->assertEquals('value', $by->global);
        $this->assertEquals('Похапе', $by->ruphp->name);
        $this->assertEquals('1', $by->pages->one);
        $this->assertEquals('Вторая', $by->pages->two);
        $this->assertEquals('Третья', \trim($by->pages->three));
        $this->assertEquals('The fourth page', \trim($by->pages->four));
        $this->assertEquals('Суп', $by->get('sub.sub')->sub['subsub']);
        $this->assertEquals('Суп', $ui->by->sub->sub->sub->subsub);

        $this->assertTrue(isset($by->calendar));
        $this->assertTrue(isset($by->pages));
        $this->assertTrue(isset($by->sub));
        $this->assertTrue($by->exists('sub'));
        $this->assertTrue($by->exists('sub.sub.sub.subsub'));
        $this->assertFalse($by->exists('sub.nosub.sub.subsub'));
        $this->assertFalse(isset($by->unknown));

        $this->assertEquals('', $ui->getKey());
        $this->assertEquals('', $by->getKey());
        $this->assertEquals('calendar', $by->calendar->getKey());
        $this->assertEquals('sub.sub.sub', $by->sub->sub->sub->getKey());
        $this->assertSame($i18n, $ui->ru->sub->sub->sub->getI18n());

        $this->setExpectedException('go\I18n\Exceptions\UIKeyNotFound');
        $ui->get('sub.nosub.sub,subsub');
    }

    public function testEmptyLocale()
    {
        $config = array(
            'languages' => array(
                'en' => true,
                'it' => 'en',
                'by' => 'it',
            ),
            'default' => 'en',
            'ui' => array(
                'dirname' => __DIR__.'/testui',
            ),
        );
        $i18n = new \go\I18n\I18n($config);
        $this->assertEquals(array('M', 'T', 'W', 'T', 'F', 'S', 'S'), $i18n->ui->by->calendar->days);
    }

    /**
     * @expectedException \go\I18n\Exceptions\ConfigService
     */
    public function testErrorUIDirname()
    {
        $config = array(
            'languages' => array(
                'en' => true,
            ),
            'default' => 'en',
        );
        $i18n = new \go\I18n\I18n($config);
        return $i18n->ui;
    }

    /**
     * @expectedException \go\I18n\Exceptions\ServiceDisabled
     */
    public function testErrorUIDisabled()
    {
        $config = array(
            'languages' => array(
                'en' => true,
            ),
            'default' => 'en',
            'ui' => false,
        );
        $i18n = new \go\I18n\I18n($config);
        return $i18n->ui;
    }
}
