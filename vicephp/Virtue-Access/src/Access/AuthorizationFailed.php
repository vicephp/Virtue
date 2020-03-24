<?php

namespace Virtue\Access;

class AuthorizationFailed extends \RuntimeException
{
    public function __construct(Identity $user, string $resource,\Throwable $previous = null)
    {
        parent::__construct("Authorization failed ({$user}, {$resource})", 403, $previous);
    }
}
