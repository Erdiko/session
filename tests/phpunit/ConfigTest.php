<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\exceptions\SessionDriverConfigException;
use erdiko\session\helpers\Config;
use tests\ErdikoTestCase;

class ConfigTest extends ErdikoTestCase
{
    const CONFIG_PATH = '/code/app/config/default/';
    const CONFIG_FILE_NAME = 'session.json';
    const CONFIG_FILE_NAME_INVALID = 'session_invalid.json';

	public function setUp()
	{
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
     * @expectedException SessionDriverConfigException
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

}
