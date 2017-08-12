<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\exceptions\SessionDriverConfigException;
use erdiko\session\helpers\Config;
use erdiko\session\Session;

use tests\ErdikoTestCase;

class ConfigTest extends ErdikoTestCase
{
    const CONFIG_PATH = '/code/app/config/default/';
    const CONFIG_FILE_NAME = 'session.json';
    const CONFIG_FILE_NAME_INVALID = 'session_invalid.json';

	public function setUp()
	{
        @session_start();
	}

	public function testValidFile()
    {
//        $config = Config::get();
//
//        $this->assertNotEmpty($config);
//        $this->assertIsArray($config);
//        $this->assertEquals($config[0], 'default');
    }

    /**
     *
     * @expectedException \erdiko\session\exceptions\SessionDriverConfigException
     */
    public function testInvalidFile()
    {
        $this->moveFile();
        Config::get();
        $this->moveFile(true);
    }

    /**
     * @param bool $rollback
     */
    private function moveFile($rollback=false)
    {
        $fromFilename = !$rollback ? self::CONFIG_FILE_NAME : self::CONFIG_FILE_NAME_INVALID;
        $toFilename = !$rollback ? self::CONFIG_FILE_NAME_INVALID : self::CONFIG_FILE_NAME;
        $path = realpath(self::CONFIG_PATH).'/';

        rename($path.$fromFilename, $path.$toFilename);
    }

	public function testValidConfig()
    {
        $this->moveFile(true);
        $config = Config::get();
        $this->assertNotEmpty($config);
        $this->assertTrue(is_array($config));
        $this->assertArrayHasKey('default', $config);
        $this->assertArrayHasKey('driver', $config['default']);
        $this->assertArrayHasKey('path', $config['default']);
    }

    public function testDotNotation()
    {
        $config = Config::getByPath('default.driver');
        $this->assertNotEmpty($config);
        $this->assertTrue(is_string($config));
        $this->assertEquals($config, 'file');
    }

    private function _moveFile($rollback=false)
    {
        $path = realpath('/code/app/config/default');
        $origFile = '/session.json';
        $invalidFile = '/session_invalid.json';

        $fromFile = $rollback ? $path.$invalidFile : $path.$origFile;
        $toFile = $rollback ? $path.$origFile : $path.$invalidFile;

        rename($fromFile, $toFile);
    }

}
