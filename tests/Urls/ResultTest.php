<?php
/**
 * Test of the folder version of urls
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\Urls;

use go\I18n\Urls\Result;

/**
 * @covers go\I18n\Urls\Result
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{

    public function testResult()
    {
        $input = array(
            'language' => 'ru',
            'multi' => true,
            'redirect' => false,
            'rel_url' => 'one/two/three/?x=1&y=2',
        );
        $expected = array(
            'language' => 'ru',
            'multi' => true,
            'redirect' => false,
            'rel_url' => 'one/two/three/?x=1&y=2',
            'rel_path' => 'one/two/three/',
            'rel_folders' => array('one', 'two', 'three'),
        );
        $result = new Result($input);
        $this->assertEquals($expected, $result->asArray());
        $this->assertSame(6, \count($result));
        $it = array();
        foreach ($result as $k => $v) {
            $it[$k] = $v;
        }
        $this->assertEquals($expected, $it);
        $this->assertSame('ru', $result->language);
        $this->assertSame(true, $result->multi);
        $this->assertSame(false, $result->redirect);
        $this->assertSame('one/two/three/?x=1&y=2', $result->rel_url);
        $this->assertSame('one/two/three/', $result->rel_path);
        $this->assertSame(array('one', 'two', 'three'), $result->rel_folders);
        $this->assertSame('ru', $result['language']);
        $this->assertSame(true, $result['multi']);
        $this->assertSame(false, $result['redirect']);
        $this->assertSame('one/two/three/?x=1&y=2', $result['rel_url']);
        $this->assertSame('one/two/three/', $result['rel_path']);
        $this->assertSame(array('one', 'two', 'three'), $result['rel_folders']);
        $this->assertTrue(isset($result->language));
        $this->assertFalse(isset($result->unknown));
        $this->assertTrue(isset($result['redirect']));
        $this->assertFalse(isset($result['unknown']));
    }

    /**
     * @param callable $func
     * @param string $exc
     * @dataProvider providerError
     */
    public function testError($func, $exc)
    {
        $input = array(
            'language' => 'ru',
            'multi' => true,
            'redirect' => false,
            'rel_url' => 'one/two/three/?x=1&y=2',
        );
        $result = new Result($input);
        $this->setExpectedException($exc);
        \call_user_func($func, $result);
    }

    public function providerError()
    {
        return array(
            array(
                function ($result) {
                    $result->language = 1;
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    $result->unkn = 1;
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    $result['language'] = 1;
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    $result['unknown'] = 1;
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    unset($result->language);
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    unset($result->unknown);
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    unset($result['language']);
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    unset($result['unknown']);
                },
                'go\I18n\Exceptions\ReadOnly',
            ),
            array(
                function ($result) {
                    return $result->unknown;
                },
                'go\I18n\Exceptions\FieldNotFound',
            ),
        );
    }
}
