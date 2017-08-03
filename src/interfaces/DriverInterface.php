<?php

namespace erdiko\session\interfaces;

/**
 * Interface Session_Driver_Interface
 *
 * @package erdiko\session\interfaces
 */
interface Session_Driver_Interface
{
    /**
     * @return mixed
     */
    public function start();

    /**
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

//    public function forget($name);
//
//    public function exists($name);
//
//    public function extend($name, $time);
//
//    public function reduce($name, $time);
//
//    public function flush();
//
//
//    public function destroy();

}