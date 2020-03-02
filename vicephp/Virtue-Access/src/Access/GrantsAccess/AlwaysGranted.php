<?php

namespace Virtue\Access\GrantsAccess;

use Virtue\Access;

class AlwaysGranted implements Access\GrantsAccess
{
    public function granted(string $resource): bool
    {
        return true;
    }
}
