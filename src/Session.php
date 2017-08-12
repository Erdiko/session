<?php

namespace erdiko\session;

use erdiko\session\exceptions\SessionDriverInvalidInterfaceException;
use erdiko\session\exceptions\SessionDriverInvalidParentException;
use erdiko\session\exceptions\SessionDriverNotExistsException;
use Pimple\Container;
use erdiko\session\helpers\Config;

/**
 * Erdiko\Session
 *
 * @method static mixed getName()
 * @method static mixed getConfig()
 * @method static mixed get($index, $expired)
 * @method static mixed set($name, $value, $lock=false, $seconds=false)
 * @method static mixed has($index)
 * @method static mixed forget($index, $force=false)
 * @method static mixed exists($index)
 * @method static mixed extend($index, $seconds)
 * @method static mixed reduce($index, $seconds)
 * @method static mixed expiresIn($index)
 * @method static mixed expired($index)
 * @method static mixed flush()
 *
 * @usage https://github.com/pinedamg/session/blob/master/advanceUsage.md
 *
 * Session::getDriverDatabase();
 * Session::database();
 * Session::get();
 *
 * @package erdiko\session
 */
final class Session
{
    const DRIVER_SOURCE_DEFAULT = 'default';
    const DRIVER_PREFIX_CLASS = 'erdiko\session\drivers\SessionDriver';

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
     * Contains the driver source name on config file
     *
     * @var string
     */
    protected $driverSource = 'default';

    /**
     * Contains the class name of the  current driver
     *
     * @var
     */
    protected $driverClassName;

    /**
     * Singleton method
     *
     * @name getInstance
     * @access protected
     * @return Session
     */
    protected static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * Session constructor
     *
     * @name __construct
     * @internal param $__construct
     * @access protected
     */
    protected function __construct()
    {
        $this->container = new Container();
        $this->loadConfig();
    }

    /**
     * Magic method to retrieve a driver or dispatch a driver method
     *
     * @name __callStatic
     * @access public
     * @param $name
     * @param $args
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        return static::getInstance()->dispatch($name, $args);
    }

    /**
     * Dispatch retrieves a session driver or dispatch a method of a default driver
     *
     * @name dispatch
     * @access public
     * @param $name
     * @param $args
     * @return mixed
     */
    public function dispatch($name, $args)
    {
        if ($driverSource = $this->getDriverSource($name)) {
            return $this->getDriver($driverSource);
        }
        return $this->getDriver()->$name(...$args);
    }

    /**
     * Check and retrieve the driver source name
     *
     * @name getDriverSource
     * @access private
     * @param $name
     * @return bool
     */
    private function getDriverSource($name)
    {
        if (strpos($name, 'getDriver')!==false) {
            $name = strtolower(str_replace('getDriver', '', $name));
        }
        return isset($this->config[$name]) ? $name : false;
    }

    /**
     * Retrieve the instance of a given Driver
     *
     * @name getDriver
     * @access private
     * @param string $driverSource
     * @return mixed
     */
    private function getDriver($driverSource='default')
    {
        if (!isset($this->container[$driverSource])) {
            $this->loadDriverClassName($driverSource);
            $this->validateDriverClassName();
            $this->setInstanceDriver();
        }
        return $this->container[$driverSource];
    }

    /**
     * Load the config session data
     *
     * @name loadConfig
     * @access private
     * @return void
     */
    private function loadConfig()
    {
        if (!$this->config) {
            $this->config = Config::get();
        }
    }

    /**
     * Check if is a valid driver class
     *
     * @name getDriverClassName
     * @access private
     * @throws SessionDriverInvalidInterfaceException
     * @throws SessionDriverInvalidParentException
     * @throws SessionDriverNotExistsException
     */
    private function validateDriverClassName()
    {
        if (!class_exists($this->driverClassName)) {
            throw new SessionDriverNotExistsException("Driver $this->driverClassName class does not exists.");
        }

        $parents = class_parents($this->driverClassName);
        if (!$parents || !array_key_exists('erdiko\session\abstracts\SessionDriverAbstract', $parents)) {
            throw new SessionDriverInvalidParentException("Driver $this->driverClassName must extends from Session_Driver_Abstract");
        }

        $interfaces = class_implements($this->driverClassName);
        if (!$interfaces || !array_key_exists('erdiko\session\interfaces\SessionDriverInterface', $interfaces)) {
            throw new SessionDriverInvalidInterfaceException("Driver $this->driverClassName must implements Session_Driver_Interface");
        }
    }

    /**
     * Set the logic to instantiate the driver class using Pimple
     *
     * @name setInstanceDriver
     * @access private
     * @internal param $driverClassName
     */
    private function setInstanceDriver()
    {
        $this->container['driverClassName'] = $this->driverClassName;
        $this->container['driverConfig'] = $this->config[$this->driverSource];
        $this->container[$this->driverSource] = function ($c) {
            return new $c['driverClassName']($c['driverConfig']);
        };
    }

    /**
     * Set the driver source name and driver class name
     *
     * @name loadDriverClassName
     * @access private
     * @param $driverSource
     */
    private function loadDriverClassName($driverSource)
    {
        $this->driverSource = $driverSource;
        $driverName = ucfirst(strtolower($this->config[$this->driverSource]['driver']));
        $this->driverClassName = self::DRIVER_PREFIX_CLASS.$driverName;
    }

}
