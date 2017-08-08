<?php

namespace erdiko\session\drivers;

use erdiko\session\abstracts\SessionDriverAbstract;
use erdiko\session\exceptions\SessionDriverConfigException;

class SessionDriverFile extends SessionDriverAbstract
{
    const DEFAULT_SAVE_PATH = '/tmp';
    const SUFFIX_LOCKED = '_locked';
    const SUFFIX_EXPIRE = '_expires';

    protected $name = 'session';
    protected $sessionConfig;

    public function _construct()
    {
        $this->initConfig();
        $this->initSession();
    }

    protected function initConfig()
    {
        $this->initSavePath();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name, $expired=false)
    {
        if ($expired) {
            //@TODO check if value is expired then return false;
        }
        return $this->getValue($name);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value, $lock=false, $seconds=false)
    {
        //@TODO add lock and expire ability
        $this->setValue($name, $value);

        if ($seconds) {
            $this->setExpireValue($name , $seconds);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function has($name)
    {
        return (!empty($_SESSION[$name]) && $this->exists($name));
    }

    /**
     * @param $name
     */
    public function forget($name, $force=false)
    {
        //@TODO add force remove
        unset($_SESSION[$name]);
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
     * @param $name
     * @param $seconds
     */
    public function extend($name, $seconds)
    {
        //@TODO add force to extend if was expired
        if (!$this->expired($name)) {
            $this->addExpireSeconds($name, $seconds);
        }
    }

    /**
     * @param $name
     * @param $seconds
     * @return bool
     */
    public function reduce($name, $seconds)
    {
        if (!$this->has($name)) {
            return false;
        }
        if (!isset($_SESSION[$name.self::SUFFIX_EXPIRE])) {
            return false;
        }
        $_SESSION[$name.self::SUFFIX_EXPIRE] -= $seconds;
    }

    /**
     *
     */
    public function flush()
    {
        unset($_SESSION);
    }

    /**
     * Initialize PHP session
     *
     * @name initSession
     * @access private
     */
    private function initSession()
    {
        if (!session_id()) {
            session_start($this->sessionConfig);
        }
    }

    /**
     * @throws SessionDriverConfigException
     */
    private function initSavePath()
    {
        $savePath = self::DEFAULT_SAVE_PATH;
        if (!$this->config['path'] || !isset($this->config['path'])) {
            $savePath = $this->config['path'];
        }
        $realSavePath = realpath($savePath);
        if(!$realSavePath) {
            throw new SessionDriverConfigException("Session File: $savePath invalid save path.");
        }
        if (!is_writable($realSavePath)) {
            throw new SessionDriverConfigException("Session File: $savePath is not writable.");
        }

        $this->sessionConfig['save_path'] = $realSavePath;
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
     * Returns the current PHP Session ID
     *
     * @name getSessionId
     * @access public
     * @return string
     */
    public function getSessionID()
    {
        return session_id();
    }

    /**
     * Return boolean, check if the given key exists in session
     *
     * @param $key
     * @return bool
     */
    private function keyExists($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @param $value
     */
    private function setValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Returns the value of the given key checking if the key exists
     *
     * @param $key
     * @return mixed
     */
    private function getValue($key)
    {
        return !$this->keyExists($key) ? false : $_SESSION[$key];
    }

    /**
     * Check if the given index exist and if has a value for the expire value
     *
     * @param $name
     * @return bool
     */
    private function hasExpire($name)
    {
        return $this->keyExists($this->getExpireKey($name));
    }

    /**
     * Return false if expire key does not exists or the expire time
     *
     * @param $key
     * @return mixed
     */
    private function getExpireValue($key)
    {
        return $this->getValue($this->getExpireKey($key));
    }

    /**
     * Returns the expire key suffix
     *
     * @param $key
     * @return string
     */
    private function getExpireKey($key)
    {
        return $key.self::SUFFIX_EXPIRE;
    }

    /**
     * Set the expire time for the given index
     *
     * @param $name
     * @param $seconds
     */
    private function setExpireValue($name, $seconds)
    {
        $expires = time() + $seconds;

        $this->setValue($this->getExpireKey($name), $expires);
    }

    /**
     * Add more seconds to expire time for the given index
     *
     * @param $name
     * @param $seconds
     */
    private function addExpireSeconds($name, $seconds)
    {
        $expires = $this->getExpireValue($name) + $seconds;

        $this->setValue($this->getExpireKey($name), $expires);
    }

}
