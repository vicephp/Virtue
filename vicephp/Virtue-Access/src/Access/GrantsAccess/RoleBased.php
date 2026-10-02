<?php

namespace Virtue\Access\GrantsAccess;

use Virtue\Access;

class RoleBased implements Access\GrantsAccess
{
    /** @var Access\Identity */
    private $identity;
    /** @var array<string, string[]> */
    private $roles = [];

    /**
     * @param array<string, string[]> $roles the roles granting each resource; a resource without roles is denied
     */
    public function __construct(Access\Identity $identity, array $roles)
    {
        $this->identity = $identity;
        $this->roles = $roles;
    }

    public function granted(string $resource): bool
    {
        $roles = $this->roles[$resource] ?? [];

        return $roles !== [] && $this->identity->hasRole($roles);
    }
}
