<?php

namespace Virtue\Access\GrantsAccess;

use Virtue\Access;

class AlwaysDenied implements Access\GrantsAccess
{
    public function granted(string $resource): bool
    {
        return false;
    }
}
