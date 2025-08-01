<?php

namespace Virtue\JWT;

class VerificationFailed extends \RuntimeException
{
    public const ON_TYPE = 1;
    public const ON_CLAIM = 2;
    public const ON_AUDIENCE = 3;
    public const ON_ISSUER = 4;
    public const ON_SUBJECT = 5;
    public const ON_ALGORITHM = 6;
    public const ON_TIME = 7;

    public function __construct(string $message = 'Could not verify signature.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
