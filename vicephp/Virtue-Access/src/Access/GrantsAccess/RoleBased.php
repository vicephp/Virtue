<?php

namespace Virtue\Access\GrantsAccess;

use Virtue\Access;

class RoleBased implements Access\GrantsAccess
{
    /** @var Access\Identity */
    private $identity;
    /** @var array */
    private $roles = [];

    public function __construct(Access\Identity $identity, array $roles)
    {
        $this->identity = $identity;
        $this->roles = $roles;
    }

    public function granted(string $resource): bool
    {
        return $this->identity->hasRole($this->roles[$resource] ?? []);
    }
}
