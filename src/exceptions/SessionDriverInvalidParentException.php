<?php

namespace erdiko\session;

use Exception;

/**
 * Class SessionDriverInvalidParentException
 *
 * @package erdiko\session
 */
class SessionDriverInvalidParentException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}