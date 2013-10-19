<?php
/**
 * Test of the parser .ui-files
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\I18n\Helpers\ParserUI;

/**
 * @covers go\I18n\Helpers\ParserUI
 */
class ParserUITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\ParserUI::parseFile
     */
    public function testParseFile()
    {
        $filename = __DIR__.'/files/testui.ui';
        $expected = array(
            'global_1' => 'One',
            'global_2' => 'Two',
            'global_3' => 'Three',
            'Days' => array(
                'name' => array('Mon', 'Tue', 'Web'),
                'short' => array('M', 'T', 'W'),
            ),
            'Section' => array(
                'var' => 'value',
                'var2' => 'value2',
                'Sub' => array(
                    'subvar' => 'subval',
                    'Subsection' => array(
                        'var' => 'value3',
                        'var4' => 'value4',
                    ),
                ),
            ),
            'dec' => array(
                'objects' => array(
                    0 => 'object',
                    1 => 'objects',
                    '__type' => 'dec',
                ),
            ),
        );
        $this->assertEquals($expected, ParserUI::parseFile($filename));
    }
}
