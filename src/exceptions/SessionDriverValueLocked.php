<?php

namespace erdiko\session\exceptions;

use Exception;

/**
 * Class SessionDriverNotExistsException
 *
 * @package erdiko\session
 */
class SessionDriverValueLocked extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}