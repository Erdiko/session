<?php

namespace erdiko\session;

use erdiko\session\exceptions\SessionDriverInvalidInterfaceException;
use erdiko\session\exceptions\SessionDriverInvalidParentException;
use erdiko\session\exceptions\SessionDriverNotExistsException;
use Pimple\Container;
use erdiko\session\helpers\Config;
use erdiko\session\exceptions\SessionDriverInvalidException;

/**
 * Erdiko\Session
 *
 * @method static mixed get($index)
 * @method static mixed set($index)
 * @method static mixed has($index)
 *
 * @package erdiko\session
 */
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
    protected static $container;

    /**
     * Config Array
     *
     * @var $config array
     */
    protected static $config;

    /**
     * Current Driver
     *
     * @var $driverName string
     */
    protected static $driverName;

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
        static::getContainer();

        if (static::isDriverSource($name)) {
            return static::getDriver($name);
        }
        return static::getDriver(static::DRIVER_NAME_DEFAULT)->$name($arguments);
    }

    private static function getDriverName($name)
    {
        if (!static::isDriver($name)) {
            throw new SessionDriverInvalidException("Session: $name is an invalid driver name.");
        }
        return static::getDriverClassName();
    }

    /**
     * @param $name
     * @return bool
     */
    private static function isDriverSource($name)
    {
        if (strpos($name, 'getDriver')!==false) {
            $name = strtolower(str_replace('getDriver', '', $name));
        }
        static::$driverName = $name;

        return isset(static::$config[$name]);
    }

    /**
     * @name getDriver
     * @param string $driver
     * @return mixed
     */
    public static function getDriver($driver='default')
    {
        return static::instanceDriver($driver);
    }

    /**
     * @name loadConfig
     * @access private
     * @return void
     */
    private static function loadConfig()
    {
        static::$config = Config::get();
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
        $driverName = ucfirst(strtolower(static::$driverName));
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
    private static function instanceDriver($driverClassName)
    {
        static::$container = new Container();
        $container['session'] = function () use ($driverClassName) {
            return new $driverClassName;
        };
    }

    private static function getContainer()
    {
        static::$container = new Container();
    }

}
