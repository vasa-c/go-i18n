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

    /**
     * @covers go\I18n\IO\Native::__construct
     */
    public function testCacheFull()
    {
        $cache = array(
            'files' => array(
                __DIR__.'/testdir/unknown.txt' => 12345,
                __DIR__.'/testdir/unkmt.txt' => true,
            ),
            'dirs' => array(
                __DIR__.'/testdir/unkdir' => true,
            ),
            'full' => true,
        );
        $params = array(
            'cache' => $cache,
        );
        $io = new Native($params);
        $this->assertFalse($io->isDir(__DIR__.'/testdir'));
        $this->assertTrue($io->isDir(__DIR__.'/testdir/unkdir'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/unkdir2'));
        $this->assertFalse($io->isDir(__DIR__.'/testdir/unknown.txt'));
        $this->assertFalse($io->isFile(__DIR__.'/testdir/test.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/unknown.txt'));
        $this->assertTrue($io->isFile(__DIR__.'/testdir/unkmt.txt'));
        $this->assertFalse($io->isFile(__DIR__.'/testdir/unknown2.txt'));
        $this->assertEquals(12345, $io->getModificationTime(__DIR__.'/testdir/unknown.txt'));
        $this->setExpectedException('go\I18n\Exceptions\IOError');
        $this->assertEquals(12345, $io->getModificationTime(__DIR__.'/testdir/test.txt'));
    }

    /**
     * @covers go\I18n\IO\Native::__construct
     */
    public function testLogger()
    {
        $td = __DIR__.'/testdir/';
        $logs = array();
        $logger = function ($method, $filename) use (&$logs, $td) {
            $filename = \substr($filename, \strlen($td));
            $logs[] = array($method, $filename);
        };
        $params = array(
            'cache' => array(
                'files' => array(
                    $td.'unknown.txt' => 12345,
                ),
            ),
            'logger' => $logger,
        );
        $io = new Native($params);

        $io->isFile($td.'unknown.txt');
        $io->isFile($td.'test.txt');
        $io->isFile($td.'undefined.txt');
        $io->isDir($td.'unkdir');
        $io->getModificationTime($td.'test.txt');
        $io->getContents($td.'test.txt');
        $io->getContentsByLines($td.'test.txt');
        $io->execPhpFile($td.'php.test.txt');
        $expectedLogs = array(
            array('isFile', 'unknown.txt'),
            array('isFile', 'test.txt'),
            array('isFile', 'undefined.txt'),
            array('isDir', 'unkdir'),
            array('getModificationTime', 'test.txt'),
            array('getContents', 'test.txt'),
            array('getContentsByLines', 'test.txt'),
            array('execPhpFile', 'php.test.txt'),
        );
        $this->assertEquals($expectedLogs, $logs);
    }

    /**
     * @covers go\I18n\IO\Native::getDirectoryContents
     */
    public function testGetDirectoryContents()
    {
        $dirname = __DIR__.'/testdir';
        $io = new Native();
        $expected = array(
            'files' => array(
                $dirname.'/php.test.txt' => true,
                $dirname.'/test.txt' => true,
            ),
            'dirs' => array(
                $dirname.'/indir' => true,
            ),
        );
        $this->assertEquals($expected, $io->getDirectoryContents($dirname));
    }

    /**
     * @covers go\I18n\IO\Native::getDirectoryContents
     */
    public function testGetDirectoryContentsRecursive()
    {
        $dirname = __DIR__.'/testdir';
        $io = new Native();
        $expected = array(
            'files' => array(
                $dirname.'/php.test.txt' => true,
                $dirname.'/test.txt' => true,
                $dirname.'/indir/1.txt' => true,
                $dirname.'/indir/2.txt' => true,
            ),
            'dirs' => array(
                $dirname.'/indir' => true,
            ),
        );
        $this->assertEquals($expected, $io->getDirectoryContents($dirname, true));
    }

    /**
     * @covers go\I18n\IO\Native::getDirectoryContents
     */
    public function testGetDirectoryContentsMtime()
    {
        $io = new Native();
        $dirname = __DIR__.'/testdir/indir';
        $expected = array(
            'files' => array(
                $dirname.'/1.txt' => \filemtime($dirname.'/1.txt'),
                $dirname.'/2.txt' => \filemtime($dirname.'/2.txt'),
            ),
            'dirs' => array(
            ),
        );
        $this->assertEquals($expected, $io->getDirectoryContents($dirname, true, true));
    }

    /**
     * @covers go\I18n\IO\Native::getDirectoryContents
     */
    public function testGetDirectoryContentsCache()
    {
        $params = array(
            'cache' => array(
                'files' => array(
                    __DIR__.'/testdir/one.txt' => 123,
                    __DIR__.'/testdir/two.txt' => 456,
                    __DIR__.'/testdir/indir/1.txt' => 'true',
                    __DIR__.'/testdir/indir/2.txt' => 547,
                    __DIR__.'/oth/1.txt' => true,
                ),
                'dirs' => array(
                    __DIR__.'/testdir/indir' => true,
                    __DIR__.'/testdir/indir/in2dir' => true,
                    __DIR__.'/oth' => true,
                    __DIR__.'/oth/indir' => true,
                ),
                'full' => true,
            ),
        );
        $io = new Native($params);
        $expectedR = array(
            'files' => array(
                __DIR__.'/testdir/one.txt' => 123,
                __DIR__.'/testdir/two.txt' => 456,
                __DIR__.'/testdir/indir/1.txt' => \filemtime(__DIR__.'/testdir/indir/1.txt'),
                __DIR__.'/testdir/indir/2.txt' => 547,
            ),
            'dirs' => array(
                __DIR__.'/testdir/indir' => true,
                __DIR__.'/testdir/indir/in2dir' => true,
            ),
        );
        $this->assertEquals($expectedR, $io->getDirectoryContents(__DIR__.'/testdir', true, true));
        $expectedP = array(
            'files' => array(
                __DIR__.'/testdir/one.txt' => 123,
                __DIR__.'/testdir/two.txt' => 456,
            ),
            'dirs' => array(
                __DIR__.'/testdir/indir' => true,
            ),
        );
        $this->assertEquals($expectedP, $io->getDirectoryContents(__DIR__.'/testdir', false, true));
    }
}
