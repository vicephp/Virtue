<?php

namespace Virtue\JWT;

class VerificationFailed extends \RuntimeException
{
    public const INVALID_TOKEN = 1;
    public const INVALID_AUDIENCE = 2;
    public const INVALID_ISSUER = 3;
    public const INVALID_SUBJECT = 4;
    public const INVALID_ALGORITHM = 4;

    public function __construct(string $message = 'Could not verify signature.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
