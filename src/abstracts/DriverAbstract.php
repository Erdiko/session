<?php

namespace erdiko\session\abstracts;

use erdiko\session\helpers\Config;
use erdiko\session\interfaces\Session_Driver_Interface;

abstract class Session_Driver_Abstract implements Session_Driver_Interface
{
    /**
     * @var $name string
     */
    protected $name;

    /**
     * @var $config array
     */
    protected $config;

    /**
     * Session_Driver_Abstract constructor.
     */
    final public function __construct()
    {
        $this->init();
        $this->_construct();
    }

    /**
     * @name _construct
     * @access public
     * @return void
     */
    protected function _construct()
    {
        // Add your own logic to change the initialization
    }

    /**
     * @name getName
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @name getConfig
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    protected function loadConfig()
    {
    }

}