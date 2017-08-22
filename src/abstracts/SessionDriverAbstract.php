<?php

namespace erdiko\session\abstracts;

use ArrayAccess;
use erdiko\session\exceptions\SessionDriverConfigException;
use erdiko\session\exceptions\SessionDriverValueLocked;
use erdiko\session\interfaces\SessionDriverInterface;

abstract class SessionDriverAbstract implements SessionDriverInterface, ArrayAccess
{
    const SUFFIX_LOCKED = '_locked';
    const SUFFIX_EXPIRE = '_expires';

    /**
     * Contains the session name
     *
     * @access protected
     * @var string
     */
    protected $name;

    /**
     * Contains the config array of the driver
     *
     * @access protected
     * @var $config array
     */
    protected $config;

    /**
     * Session_Driver_Abstract constructor.
     *
     * @name __construct
     * @param $config array
     * @throws SessionDriverConfigException
     */
    final public function __construct($config)
    {
        if (!$this->name) {
            throw new SessionDriverConfigException("The name value is required for class ".static::class);
        }
        $this->config = $config;
        $this->_construct();
    }

    /**
     * Secondary constructor Session Driver
     *
     * @name _construct
     * @access public
     * @return void
     */
    protected function _construct()
    {
        // Add your own logic to customize the initialization of the driver
    }

    /**
     * Returns the name of the Driver
     *
     * @name getName
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @name getConfig
     * @access public
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @name keyExists
     * @access protected
     * @abstract
     * @param $key
     * @return mixed
     */
    abstract protected function keyExists($key);

    /**
     * @name setValue
     * @access protected
     * @abstract
     * @param $key
     * @param $value
     * @return mixed
     */
    abstract protected function setValue($key, $value);

    /**
     * @name getValue
     * @access protected
     * @abstract
     * @param $key
     * @return mixed
     */
    abstract protected function &getValue($key);

    /**
     * Remove value by key
     *
     * @name unsetValue
     * @access protected
     * @abstract
     * @param $key
     * @return mixed
     */
    abstract protected function unsetValue($key);

    /**
     * Remove all values
     *
     * @name flush
     * @access protected
     * @abstract
     * @param $force
     * @return mixed
     */
    abstract public function flush($force);

    /**
     * Remove value by key
     *
     * @name isEmpty
     * @access protected
     * @abstract
     * @param $key
     * @return mixed
     */
    abstract protected function isEmpty($key);

    /**
     *
     */
    public function start()
    {

    }

    /**
     * Retrieve given value by index
     *
     * @name get
     * @access public
     * @param $name
     * @return mixed
     */
    public function &get($name, $expired=false)
    {
        if (!$expired && $this->expired($name)) {
            return false;
        }
        return $this->getValue($name);
    }

    /**
     * @param $name
     * @param $value
     * @param bool $lock
     * @param bool $seconds
     * @return mixed
     * @throws SessionDriverValueLocked
     * @internal param $set
     * @access public
     * @internal param $set
     */
    public function set($name, $value, $lock=false, $seconds=false)
    {
        if ($this->keyExists($name) && $this->hasLocked($name)) {
            throw new SessionDriverValueLocked("The key name $name is already set and locked.");
        }
        $this->setValue($name, $value);

        if ($seconds) {
            $this->setExpireValue($name, $seconds);
        }
        if ($lock) {
            $this->setAsLocked($name);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function has($name)
    {
        return (!$this->isEmpty($name) && $this->exists($name));
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name)
    {
        return $this->keyExists($name);
    }

    /**
     * @name forget
     * @param $name
     * @param bool $force
     * @return mixed|void
     * @throws SessionDriverValueLocked
     * @internal param $forget
     */
    public function forget($name, $force=false)
    {
        if ($this->hasLocked($name) && !$force) {
            throw new SessionDriverValueLocked("The key name $name is locked.");
        }
        $this->unsetValue($name);
    }

    /**
     * @mname extend
     * @param $name
     * @param $seconds
     * @return mixed|void
     */
    public function extend($name, $seconds)
    {
        //@TODO add force to extend if was expired
        if (!$this->expired($name)) {
            $this->addExpireSeconds($name, $seconds);
        }
    }

    /**
     * @name reduce
     * @param $name
     * @param $seconds
     * @return bool
     */
    public function reduce($name, $seconds)
    {
        if (!$this->has($name) || !$this->hasExpire($name)) {
            return false;
        }
        $this->reduceExpireSeconds($name, $seconds);
    }

    /**
     * Check if the given index exist and if has a value for the expire value
     *
     * @name hasExpire
     * @access protected
     * @param $name
     * @return bool
     */
    public function hasExpire($name)
    {
        return $this->keyExists($this->getExpireKey($name));
    }

    /**
     * Returns the seconds for the value to expire.
     *
     * @param $name
     * @return bool
     */
    public function expiresIn($name)
    {
        return $this->hasExpire($name) ? $this->getExpireValue($name) : false;
    }

    /**
     * Returns if the value is expired
     *
     * @name expired
     * @param $name
     * @return bool
     */
    public function expired($name)
    {
        if (!$this->hasExpire($name)) {
            return false;
        }
        return time() > $this->getExpireValue($name);
    }

    /**
     * Return false if expire key does not exists or the expire time
     *
     * @name getExpireValue
     * @access protected
     * @param $key
     * @return mixed
     */
    public function getExpireValue($key)
    {
        return $this->getValue($this->getExpireKey($key));
    }

    /**
     * Set the expire time for the given index
     *
     * @name setExpireValue
     * @access protected
     * @param $name
     * @param $seconds
     */
    protected function setExpireValue($name, $seconds)
    {
        $expires = time() + $seconds;

        $this->setValue($this->getExpireKey($name), $expires);
    }

    /**
     * Returns the expire key suffix
     *
     * @name getExpireKey
     * @access protected
     * @param $key
     * @return string
     */
    protected function getExpireKey($key)
    {
        return $key.self::SUFFIX_EXPIRE;
    }

    /**
     * Add more seconds to expire time for the given index
     *
     * @name addExpireSeconds
     * @access protected
     * @param $name
     * @param $seconds
     */
    protected function addExpireSeconds($name, $seconds)
    {
        $expires = $this->getExpireValue($name) + $seconds;

        $this->setValue($this->getExpireKey($name), $expires);
    }

    /**
     * Reduce more seconds to expire time for the given index
     *
     * @name reduceExpireSeconds
     * @access protected
     * @param $name
     * @param $seconds
     */
    protected function reduceExpireSeconds($name, $seconds)
    {
        $expires = $this->getExpireValue($name) - $seconds;

        $this->setValue($this->getExpireKey($name), $expires);
    }

    /**
     * @name setAsLocked
     * @access protected
     * @param $key
     */
    protected function setAsLocked($key)
    {
        $this->setValue($this->getLockedKey($key), true);
    }

    /**
     * @name hasLocked
     * @access protected
     * @param $key
     * @return bool
     */
    protected function hasLocked($key)
    {
        return $this->keyExists($this->getLockedKey($key));
    }

    /**
     * @name getLockedKey
     * @access protected
     * @param $key
     * @return string
     */
    protected function getLockedKey($key)
    {
        return $key.self::SUFFIX_LOCKED;
    }

    /**
     * @name offsetExists
     * @access public
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * @name offsetGet
     * @access public
     * @param mixed $offset
     * @return bool
     */
    public function &offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @name offsetSet
     * @param mixed $offset
     * @param mixed $value
     * @return bool
     * @access public
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @name offsetUnset
     * @access public
     * @param mixed $offset
     * @return bool
     */
    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    /**
     * @name __destruct
     * @access public
     * @return void
     */
    public function __destruct()
    {
        // Add your own logic to customize on destruct of the driver
    }

}
