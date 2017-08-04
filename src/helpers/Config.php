<?php

namespace erdiko\session\helpers;

use erdiko\core\Helper;

class Config
{
    const CONFIG_NAME = 'session';

    protected static $config;

    /**
     * @name getConfig
     * @access private
     * @static
     * @return mixed
     */
    private static function getConfig()
    {
        if (!static::$config) {
            static::$config = Helper::getConfig(static::CONFIG_NAME);
        }
        return static::$config;
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
//        $latestIndex = end($index);
        $current = $config;

        foreach ($indexes as $index) {
            if (!isset($current[$index])) {
                return false;
            }
//            if ($index == $latestIndex) {
//                return $current[$index];
//            }
            $current = $current[$index];
        }
        return $current;
    }

}