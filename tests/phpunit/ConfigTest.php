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

    /**
     *
     * @expectedException \erdiko\session\exceptions\SessionDriverConfigException
     */
    public function testInvalidFile()
    {
        $this->moveFile();
        Config::get();
    }

	public function testValidConfig()
    {
        $this->moveFile(true);
        $config = Config::get();
        $this->assertNotEmpty($config);
        $this->assertTrue(is_array($config));
        $this->assertArrayHasKey('default', $config);
        $this->assertArrayHasKey('driver', $config['default']);
        $this->assertArrayHasKey('save_path', $config['default']['session_config']);
    }

    public function testDotNotation()
    {
        $config = Config::getByPath('default.driver');
        $this->assertNotEmpty($config);
        $this->assertTrue(is_string($config));
        $this->assertEquals($config, 'file');
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

}
