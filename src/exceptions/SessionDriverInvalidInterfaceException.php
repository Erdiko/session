<?php

namespace erdiko\session\exceptions;

use Exception;

/**
 * Class SessionDriverInvalidInterfaceException
 *
 * @package erdiko\session
 */
class SessionDriverInvalidInterfaceException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
