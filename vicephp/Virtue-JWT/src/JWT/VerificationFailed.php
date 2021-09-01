<?php

namespace Virtue\JWT;

class VerificationFailed extends \RuntimeException
{
    public function __construct($message = 'Could not verify signature.', $code = 0, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
