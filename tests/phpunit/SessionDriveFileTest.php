<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\drivers\SessionDriverFile;
use erdiko\session\helpers\Config;
use erdiko\session\Session;
use tests\ErdikoTestCase;

class SessionDriveFileTest extends ErdikoTestCase
{
    public $sessionDriverFile;

    public function setUp()
    {
        $this->sessionDriverFile = new SessionDriverFile(Config::get('default'));
    }

    public function testSet()
    {
        $this->sessionDriverFile->set('index', 'value');

        $this->assertNotEmpty($_SESSION);
        $this->assertTrue(isset($_SESSION['index']));
        $this->assertEquals($_SESSION['index'], 'value');
    }

    public function testGet()
    {
        $this->sessionDriverFile->set('index', 'value');
        $value = $this->sessionDriverFile->get('index');

        $this->assertNotEmpty($value);
        $this->assertEquals($value, 'value');
    }

    public function testHas()
    {
        $this->sessionDriverFile->set('index', 'value');
        $has = $this->sessionDriverFile->has('index');

        $this->assertNotEmpty($has);
        $this->assertEquals($has, true);
    }

    public function testHasFalse()
    {
        $has = $this->sessionDriverFile->has('index');

        $this->assertEquals($has, false);
    }

    public function testExists()
    {
        $this->sessionDriverFile->set('index', 'value');
        $exists = $this->sessionDriverFile->exists('index');

        $this->assertEquals($exists, true);
    }

    public function testExistsFalse()
    {
        $exists = $this->sessionDriverFile->exists('index');

        $this->assertEquals($exists, false);
    }

    public function testForget()
    {
        $this->sessionDriverFile->forget('index');

        $this->assertEquals(isset($_SESSION['index']), false);
        $this->assertEquals(empty($_SESSION), true);
    }

}
