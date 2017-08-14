<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\drivers\SessionDriverPredis;
use erdiko\session\helpers\Config;
use tests\ErdikoTestCase;

class SessionDriverPredisTest extends ErdikoTestCase
{
    public $sessionDriverPredis;

    public function setUp()
    {
        $this->sessionDriverPredis = new SessionDriverPredis(Config::get('predis'));
    }

    public function testSet()
    {
        $this->sessionDriverPredis->set('index', 'value');
        $value = $this->sessionDriverPredis->get('index');

        $this->assertNotEmpty($value);
        $this->assertEquals($value, 'value');
    }

    public function testGet()
    {
        $this->sessionDriverPredis->set('index', 'value');
        $value = $this->sessionDriverPredis->get('index');

        $this->assertNotEmpty($value);
        $this->assertEquals($value, 'value');
    }

    public function testHas()
    {
        $this->sessionDriverPredis->set('index', 'value');
        $has = $this->sessionDriverPredis->has('index');

        $this->assertNotEmpty($has);
        $this->assertEquals($has, true);
    }

    public function testHasFalse()
    {
        $this->sessionDriverPredis->forget('index');
        $has = $this->sessionDriverPredis->has('index');

        $this->assertEquals($has, false);
    }

    public function testExists()
    {
        $this->sessionDriverPredis->set('index', 'value');
        $exists = $this->sessionDriverPredis->exists('index');

        $this->assertEquals($exists, true);
    }

    public function testExistsFalse()
    {
        $this->sessionDriverPredis->forget('index');
        $exists = $this->sessionDriverPredis->exists('index');

        $this->assertEquals($exists, false);
    }

    public function testForget()
    {
        $this->sessionDriverPredis->set('index', 'value');
        $this->sessionDriverPredis->forget('index');
        $value = $this->sessionDriverPredis->exists('index');

        $this->assertEquals($value, false);
    }

}
