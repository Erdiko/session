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
     * @name get
     * @param $name
     * @return mixed
     */
    public function get($name);

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value);

    /**
     * @param $name
     * @return mixed
     */
    public function has($name);

    public function forget($name);

    public function exists($name);

    public function extend($name, $time);

    public function reduce($name, $time);

    public function expiresIn($name);

    public function expired($name);

    public function flush();

}