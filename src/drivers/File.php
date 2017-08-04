<?php

namespace erdiko\session\drivers;

use erdiko\session\abstracts\DriverAbstract;

class Session_Driver_File extends Session_Driver_Abstract
{
    /**
     * @return mixed
     */
    public function start()
    {
        if (isset($this->_config['save_path']))
        {
            $this->_config['save_path'] = rtrim($this->_config['save_path'], '/\\');
            ini_set('session.save_path', $this->_config['save_path']);
        }
        else
        {
            log_message('debug', 'Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.');
            $this->_config['save_path'] = rtrim(ini_get('session.save_path'), '/\\');
        }
        $this->_sid_regexp = $this->_config['_sid_regexp'];
        isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {
        // TODO: Implement set() method.
    }

    /**
     * @param $name
     * @return mixed
     */
    public function has($name)
    {
        // TODO: Implement has() method.
    }
}