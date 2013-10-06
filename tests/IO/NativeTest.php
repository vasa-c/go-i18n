<?php
/**
 * Test of native I/O implementation
 *
 * @package go\I18n
 * @subpackage Tests
 * @author Grigoriev Oleg aka vasa_c <go.vasac@gmail.com>
 */

namespace go\Tests\I18n\IO;

use go\I18n\IO\Native;

/**
 * @covers go\I18n\IO\Native
 */
class NativeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers go\I18n\IO\Native::isFile
     */
    public function testIsFile()
    {
        $io = new Native();
        $this->assertTrue($io->isFile(__DIR__.'/testdir/test.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/php.test.txt'));
        $this->assertFalse($io->isFile(__DIR__.'/testdir/unknown.txt'));
        $this->assertFalse($io->isFile(__DIR__));
    }

    /**
     * @covers go\I18n\IO\Native::isDir
     */
    public function testIsDir()
    {
        $io = new Native();
        $this->assertTrue($io->isDir(__DIR__.'/testdir'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/test.txt'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/unknown'));
    }

    /**
     * @covers go\I18n\IO\Native::getModificationTime
     */
    public function testGetModificationTime()
    {
        $io = new Native();
        $filename = __DIR__.'/testdir/test.txt';
        $this->assertEquals(\filemtime($filename), $io->getModificationTime($filename));
        $this->setExpectedException('go\I18n\Exceptions\IOError');
        $io->getModificationTime(__DIR__.'/testdir/unknown.txt');
    }

    /**
     * @covers go\I18n\IO\Native::getContents
     */
    public function testGetContents()
    {
        $io = new Native();
        $filename = __DIR__.'/testdir/test.txt';
        $expected = "This is text file.\n\nSecond line\nThird line";
        $this->assertEquals($expected, \trim($io->getContents($filename)));
        $this->setExpectedException('go\I18n\Exceptions\IOError');
        $io->getContents(__DIR__.'/testdir/unknown.txt');
    }

    /**
     * @covers go\I18n\IO\Native::getContentsByLines
     */
    public function testContentsByLines()
    {
        $io = new Native();
        $filename = __DIR__.'/testdir/test.txt';
        $expected = array(
            'This is text file.',
            'Second line',
            'Third line',
        );
        $this->assertEquals($expected, $io->getContentsByLines($filename));
        $this->setExpectedException('go\I18n\Exceptions\IOError');
        $io->getContentsByLines(__DIR__.'/testdir/unknown.txt');
    }

    /**
     * @covers go\I18n\IO\Native::execPhpFile
     */
    public function testExecPhpFile()
    {
        $io = new Native();
        $filename = __DIR__.'/testdir/php.test.txt';
        $this->assertEquals(123, $io->execPhpFile($filename));
    }

    /**
     * @covers go\I18n\IO\Native::__construct
     */
    public function testCache()
    {
        $cache = array(
            'files' => array(
                __DIR__.'/testdir/unknown.txt' => 12345,
                __DIR__.'/testdir/unkmt.txt' => true,
            ),
            'dirs' => array(
                __DIR__.'/testdir/unkdir' => true,
            ),
        );
        $params = array(
            'cache' => $cache,
        );
        $io = new Native($params);
        $this->assertTrue($io->isDir(__DIR__.'/testdir'));
        $this->assertTrue($io->isDir(__DIR__.'/testdir/unkdir'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/unkdir2'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/unknown.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/test.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/unknown.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/unkmt.txt'));
        $this->assertFalse($io->isFile(__DIR__.'/testdir/unknown2.txt'));
        $this->assertEquals(12345, $io->getModificationTime(__DIR__.'/testdir/unknown.txt'));
        $this->setExpectedException('go\I18n\Exceptions\IOError');
        $this->assertEquals(12345, $io->getModificationTime(__DIR__.'/testdir/unkmt.txt'));
    }
}