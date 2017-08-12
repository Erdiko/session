<?php

namespace erdiko\session\exceptions;

use Exception;

/**
 * Class SessionDriverInvalidException
 *
 * @package erdiko\session
 */
class SessionDriverInvalidException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}