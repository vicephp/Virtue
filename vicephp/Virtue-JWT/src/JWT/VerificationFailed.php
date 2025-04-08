<?php

namespace Virtue\JWT;

class VerificationFailed extends \RuntimeException
{
    public function __construct(string $message = 'Could not verify signature.', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
