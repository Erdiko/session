<?php

namespace erdiko\session;

use erdiko\session\abstracts\Session_Driver_Abstract;
use Pimple\Container;
use erdiko\session\helpers\Config;

final class Session
{
    const DRIVER_PREFIX_CLASS = 'Session_Driver_';

    /**
     * @var $instance Session
     */
    protected static $instance;

    /**
     * @var $container Container
     */
    protected $container;

    /**
     * @var $config array
     */
    protected $config;

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
     * Session constructor.
     *
     * @name __construct
     * @access protected
     */
    protected function __construct()
    {
        if (!$this->container) {
            $this->initDriver();
        }
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
     * @name getDriver
     * @param string $driver
     * @return mixed
     */
    public static function getDriver($driver='default')
    {
        return static::getInstance()->container[$driver];
    }

    /**
     *
     */
    private function initDriver()
    {
        $this->loadConfig();
        $this->instanceDriver($this->getDriverClassName('default'));
    }

    private function loadConfig()
    {
        $this->config = Config::get();
    }

    private function getDriverClassName($driver)
    {
        $driverName = ucfirst(strtolower($this->config[$driver]));
        $driverClassName = self::DRIVER_PREFIX_CLASS.$driverName;

        if (!class_exists($driverClassName)) {
            throw new SessionDriverNotExistsException("Driver $driverClassName class does not exists.");
        }

        $parents = class_parents($driverClassName);
        if (!$parents || !in_array('Session_Driver_Abstract', $parents)) {
            throw new SessionDriverInvalidParentException("Driver $driverClassName must implements Session_Driver_Interface");
        }

        $interfaces = class_implements($driverClassName);
        if (!$interfaces || !in_array('Session_Driver_Interface', $interfaces)) {
            throw new SessionDriverInvalidInterfaceException("Driver $driverClassName must implements Session_Driver_Interface");
        }

        return $driverClassName;
    }

    private function instanceDriver($driverClassName)
    {
        $this->container = new Container();
        $container['session'] = function ($c) {
            return new Session($c['session_storage']);
        };
    }

}
