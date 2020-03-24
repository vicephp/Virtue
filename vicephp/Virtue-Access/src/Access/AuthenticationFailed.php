<?php

namespace Virtue\Access;

class AuthenticationFailed extends \RuntimeException
{
    public function __construct(Identity $user, \Throwable $previous = null)
    {
        parent::__construct("Authentication failed ({$user})", 401, $previous);
    }
}
