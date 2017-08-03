<?php

namespace erdiko\session;

use Pimple\Container;

final class Session
{
    /**
     * @var $instance Session
     */
    protected static $instance;

    /**
     * @var $container Container
     */
    protected $container;

    /**
     * Session constructor.
     *
     * @name __construct
     * @access protected
     */
    protected function __construct()
    {
        if (!$this->container) {
            $this->initDefaultDriver();
        }
    }

    /**
     * @name getInstance
     * @return mixed
     */
    protected static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getDriver()->$name($arguments);
    }

    /**
     * @param string $driver
     * @return mixed
     */
    public static function getDriver($driver='default')
    {
        return static::getInstance()->container[$driver];
    }

    private function initDefaultDriver()
    {
        $this->loadConfigDriver();
        $this->instanceDriver();
    }

    private function loadConfigDriver()
    {
    }

    private function instanceDriver()
    {
        $this->container = new Container();
        $container['session'] = function ($c) {
            return new Session($c['session_storage']);
        };
    }

}
