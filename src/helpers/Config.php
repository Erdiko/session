<?php

namespace erdiko\session\helpers;

use erdiko\core\Helper;
use erdiko\session\exceptions\SessionDriverConfigException;

class Config
{
    const CONFIG_NAME = 'session';

    /**
     * @var $config
     * @static
     * @access protected
     */
    protected static $config;

    /**
     * @return mixed
     * @throws SessionDriverConfigException
     * @internal param $getConfig
     * @access private
     * @static
     */
    private static function getConfig()
    {
        try {
            if (!static::$config) {
                static::$config = Helper::getConfig(static::CONFIG_NAME);
            }
            return static::$config;
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'file not found')!==false) {
                throw new SessionDriverConfigException('Session config file '.static::CONFIG_NAME.'.json not found.');
            }
            if (strpos($e->getMessage(), 'parse error')!==false) {
                throw new SessionDriverConfigException('Session config '.static::CONFIG_NAME.'.json has invalid json data.');
            }
        }
    }

    /**
     * @name get
     * @access public
     * @static
     * @param null $path
     * @return array|bool|mixed
     */
    public static function get($path=null)
    {
        if (!$path) {
            return static::getConfig();
        }
        return static::getByPath($path);
    }

    /**
     * @name getByPath
     * @param $path
     * @access public
     * @static
     * @return array|bool|mixed
     */
    public static function getByPath($path)
    {
        $config = static::getConfig();
        $indexes = explode('.', $path);
        $current = $config;

        foreach ($indexes as $index) {
            if (!isset($current[$index])) {
                return false;
            }
            $current = $current[$index];
        }
        return $current;
    }

}