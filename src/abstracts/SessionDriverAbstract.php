<?php

namespace erdiko\session\abstracts;

use erdiko\session\helpers\Config;
use erdiko\session\interfaces\SessionDriverInterface;

abstract class SessionDriverAbstract implements SessionDriverInterface
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
     *
     * @param $config array
     */
    final public function __construct($config)
    {
        $this->config = $config;
        $this->_construct();
    }

    /**
     * @name _construct
     * @access public
     * @return void
     */
    protected function _construct()
    {
        // Add your own logic to customize the initialization of the driver
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

    public function __destruct()
    {
        // Add your own logic to customize the destruct of the driver
    }

}