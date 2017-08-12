<?php

namespace erdiko\session\interfaces;

/**
 * Interface SessionDriverInterface
 *
 * @package erdiko\session\interfaces
 */
interface SessionDriverInterface
{

    /**
     * @param $name
     * @param bool $expired
     * @return mixed
     * @internal param $get
     */
    public function get($name, $expired=false);

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value, $lock=false, $seconds=false);

    /**
     * @param $name
     * @return mixed
     */
    public function has($name);

    /**
     * @param $name
     * @return mixed
     */
    public function forget($name, $force=false);

    /**
     * @param $name
     * @return mixed
     */
    public function exists($name);

    /**
     * @param $name
     * @param $time
     * @return mixed
     */
    public function extend($name, $seconds);

    /**
     * @param $name
     * @param $time
     * @return mixed
     */
    public function reduce($name, $seconds);

    /**
     * @param $name
     * @return mixed
     */
    public function expiresIn($name);

    /**
     * @param $name
     * @return mixed
     */
    public function expired($name);

    /**
     * @param $force
     * @return mixed
     */
    public function flush($force);

}