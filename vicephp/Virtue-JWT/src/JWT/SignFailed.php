<?php

namespace Virtue\JWT;

class SignFailed extends \RuntimeException
{
    public function __construct(string $message = 'Could not sign token.', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
