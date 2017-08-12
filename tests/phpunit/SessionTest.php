<?php

namespace tests\phpunit;

require_once dirname(__DIR__) . '/ErdikoTestCase.php';

use erdiko\session\Session;
use tests\ErdikoTestCase;

class SessionTest extends ErdikoTestCase
{

    public function setUp()
    {
    }

    public function testSet()
    {
        Session::set('index', 'value');

        $this->assertNotEmpty($_SESSION);
        $this->assertTrue(isset($_SESSION['index']));
        $this->assertEquals($_SESSION['index'], 'value');
    }

    public function testGet()
    {
        Session::set('index', 'value');
        $value = Session::get('index');

        $this->assertNotEmpty($value);
        $this->assertEquals($value, 'value');
    }

    public function testHas()
    {
        Session::set('index', 'value');
        $has = Session::has('index');

        $this->assertNotEmpty($has);
        $this->assertEquals($has, true);
    }

    public function testHasFalse()
    {
        $has = Session::has('index');

        $this->assertEquals($has, false);
    }

    public function testExists()
    {
        Session::set('index', 'value');
        $exists = Session::exists('index');

        $this->assertEquals($exists, true);
    }

    public function testExistsFalse()
    {
        $exists = Session::exists('index');

        $this->assertEquals($exists, false);
    }

    public function testForget()
    {
        Session::forget('index');

        $this->assertEquals(isset($_SESSION['index']), false);
        $this->assertEquals(empty($_SESSION), true);
    }

}
