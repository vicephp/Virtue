<?php

namespace Virtue\Access;

interface GrantsAccess
{
    public function granted(string $resource): bool;
}