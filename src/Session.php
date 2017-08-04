<?php

namespace erdiko\session;

use Pimple\Container;
use erdiko\session\helpers\Config;

final class Session
{
    const DRIVER_NAME_DEFAULT = 'default';
    const DRIVER_PREFIX_CLASS = 'Session_Driver_';

    /**
     * Contains the singleton instance
     *
     * @var $instance Session
     */
    protected static $instance;

    /**
     * Contains the Pimple\Container instance
     *
     * @var $container Container
     */
    protected $container;

    /**
     * Config Array
     *
     * @var $config array
     */
    protected $config;

    /**
     * Current Driver
     *
     * @var $driverName string
     */
    protected $driverName;

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

    protected function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Session::getDatabase()
     * Session::database()->
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!$driverName = static::getDriverName($name)) {
            $driverName = static::DRIVER_PREFIX_CLASS;
        }
        return static::getDriver($driverName)->$name($arguments);
    }

    private static function getDriverName($name)
    {
        if (!static::isDriver($name)) {
            throw new SessionDriverInvalidException("Session: $name is an invalid driver name.");
        }
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
     * @name initDriver
     * @access private
     * @return void
     */
    private function initDriver()
    {
        $this->loadConfig();
        $this->instanceDriver($this->getDriverClassName());
    }

    /**
     * @name loadConfig
     * @access private
     * @return void
     */
    private function loadConfig()
    {
        $this->config = Config::get();
    }

    /**
     * @name getDriverClassName
     * @access private
     * @return string
     * @throws SessionDriverInvalidInterfaceException
     * @throws SessionDriverInvalidParentException
     * @throws SessionDriverNotExistsException
     */
    private function getDriverClassName()
    {
        $driverName = ucfirst(strtolower($this->config[$this->driver]));
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

    /**
     * @name instanceDriver
     * @access private
     * @param $driverClassName
     */
    private function instanceDriver($driverClassName)
    {
        $this->container = new Container();
        $container['session'] = function () use ($driverClassName) {
            return new $driverClassName;
        };
    }

    private static function isDriver($name)
    {
        if (strpos($name, 'getDriver')!==false) {
            return strtolower(str_replace('getDriver', '', $name));
        }
        if (isset($this->config[$name])) {

        }
    }

}
