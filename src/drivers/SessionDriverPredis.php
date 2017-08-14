<?php

namespace erdiko\session\drivers;

use erdiko\session\abstracts\SessionDriverAbstract;
use Predis\Client;

class SessionDriverPredis extends SessionDriverAbstract
{

    /**
     * Contains the session name
     *
     * @access protected
     * @var string
     */
    protected $name = 'predis';

    /**
     * @var null
     */
    protected $cli = null;

    /**
     * Optional Constructor
     *
     * @name _construct
     * @access protected
     * @return void
     */
    protected function _construct()
    {
        $this->initConfig();
        $this->initPredis();
    }

    private function initConfig()
    {
        // @TODO add validation for config file
    }

    private function initPredis()
    {
        $this->cli = new Client($this->config['config']);
    }

    /**
     * @name keyExists
     * @access protected
     * @param $key
     * @return mixed
     */
    protected function keyExists($key)
    {
        return $this->cli->exists($key);
    }

    /**
     * @name setValue
     * @access protected
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function setValue($key, $value)
    {
        $this->cli->set($key, $value);
    }

    /**
     * @name getValue
     * @access protected
     * @param $key
     * @return mixed
     */
    protected function getValue($key)
    {
        return $this->cli->get($key);
    }

    /**
     * Remove value by key
     *
     * @name unsetValue
     * @access protected
     * @param $key
     * @return mixed
     */
    protected function unsetValue($key)
    {
        $this->cli->del($key);
    }

    /**
     * Remove all values
     *
     * @name flush
     * @access protected
     * @param $force
     * @return mixed
     */
    public function flush($force=false)
    {
        $this->cli->flushall();
    }

    /**
     * @name isEmpty
     * @access protected
     * @param $key
     * @return mixed
     */
    protected function isEmpty($key)
    {
        return empty($this->get($key));
    }

}