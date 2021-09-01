<?php

namespace Virtue\JWT;

class SignFailed extends \RuntimeException
{
    public function __construct($message = 'Could not sign token.', $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
