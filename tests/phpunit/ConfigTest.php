<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\exceptions\SessionDriverConfigException;
use erdiko\session\helpers\Config;
use erdiko\session\Session;
use tests\ErdikoTestCase;

class ConfigTest extends ErdikoTestCase
{
	public function setUp()
	{
        @session_start();
	}

    /**
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
        $this->assertArrayHasKey('path', $config['default']);
    }

    public function testDotNotation()
    {
        $config = Config::getByPath('default.driver');
        $this->assertNotEmpty($config);
        $this->assertTrue(is_string($config));
        $this->assertEquals($config, 'file');
    }

    private function moveFile($rollback=false)
    {
        $path = realpath('/code/app/config/default');
        $origFile = '/session.json';
        $invalidFile = '/session_invalid.json';

        $fromFile = $rollback ? $path.$invalidFile : $path.$origFile;
        $toFile = $rollback ? $path.$origFile : $path.$invalidFile;

        rename($fromFile, $toFile);
    }

}
