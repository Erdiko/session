<?php

namespace erdiko\session\drivers;

use erdiko\session\abstracts\SessionDriverAbstract;
use erdiko\session\exceptions\SessionDriverConfigException;

class SessionDriverFile extends SessionDriverAbstract
{
    const DEFAULT_SAVE_PATH = '/tmp';

    /**
     * Contains the session name
     *
     * @access protected
     * @var string
     */
    protected $name = 'session';

    /**
     * Contains the config array of the driver
     *
     * @access protected
     * @var $config array
     */
    protected $sessionConfig;

    /**
     * SessionDriverFile constructor
     *
     * @name __construct
     * @access public
     * @return SessionDriverFile
     */
    public function _construct()
    {
        $this->initConfig();
        $this->initSession();
    }

    /**
     * @name initConfig
     * @access protected
     * @return void
     */
    protected function initConfig()
    {
        //@TODO improve config adding more values to customize
        $this->initSavePath();
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
     * @name keyExists
     * @access protected
     * @param $key
     * @return bool
     */
    protected function keyExists($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @name setValue
     * @access protected
     * @param $key
     * @param $value
     * @return mixed|void
     */
    protected function setValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Returns the value of the given key checking if the key exists
     *
     * @name getValue
     * @access protected
     * @param $key
     * @return mixed
     */
    protected function getValue($key)
    {
        return !$this->keyExists($key) ? false : $_SESSION[$key];
    }

    /**
     * Removes the value by key
     *
     * @name unsetValue
     * @access protected
     * @param $key
     * @return mixed|void
     */
    protected function unsetValue($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @name flush
     * @access public
     * @param bool $force
     * @return mixed|void
     */
    public function flush($force=false)
    {
        unset($_SESSION);
    }

    /**
     * Check if the value is empty
     *
     * @name emptyValue
     * @access protected
     * @param $key
     * @return bool
     */
    protected function isEmpty($key)
    {
        return empty($_SESSION[$key]);
    }

}
