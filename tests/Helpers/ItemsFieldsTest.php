<?php
/**
 * Test of the ItemsFields helper
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Helpers;

use go\I18n\Helpers\ItemsFields;

/**
 * @covers go\I18n\Helpers\ItemsFields
 */
class ItemsFieldsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\Helpers\ItemsFields::createListForLoad
     */
    public function testCreateListForLoad()
    {
        $config = array(
            'fields' => array(
                'title' => 't',
                'text' => 'tx',
                'description' => 'd',
                'qwerty' => 'q',
            ),
        );
        $fields = array('title', 'text', 'description');
        $items = array(
            10 => array(
                'title' => 'sadfdstf',
                'text' => 'esretret',
                'description' => 'retretyrty',
            ),
            11 => array(
                'title' => 'dstfrtrt',
                'qwerty' => 'dstrret',
            ),
            15 => array(
                'title' => 'dstftg',
                'text' => 'dstfretrt5y',
                'unknown' => 'ertret',
            ),
        );
        $expected = array(
            'cids' => array(11, 15),
            'fields' => array('tx', 'd'),
        );
        $this->assertEquals($expected, ItemsFields::createListForLoad($items, $fields, $config));
    }

    /**
     * @covers go\I18n\Helpers\ItemsFields::createLoadedList
     */
    public function testCreateLoadedList()
    {
        $config = array(
            'fields' => array(
                'title' => 't',
                'text' => 'tx',
                'description' => 'd',
                'qwerty' => 'q',
            ),
        );
        $fields = array('title', 'text', 'description');
        $cids = array(5, 6, 7);
        $result = array(
            5 => array(
                't' => 'title 5',
                'tx' => 'text 5',
            ),
            7 => array(
                't' => 'title 7',
                'd' => 'descript 8',
            ),
        );
        $expected = array(
            5 => array(
                'title' => 'title 5',
                'text' => 'text 5',
                'description' => '',
            ),
            6 => array(
                'title' => '',
                'text' => '',
                'description' => '',
            ),
            7 => array(
                'title' => 'title 7',
                'text' => '',
                'description' => 'descript 8',
            ),
        );
        $this->assertEquals($expected, ItemsFields::createLoadedList($result, $cids, $fields, $config));
    }
}
