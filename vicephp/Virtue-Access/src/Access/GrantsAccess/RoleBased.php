<?php

namespace Virtue\Access\GrantsAccess;

use Virtue\Access;

class RoleBased implements Access\GrantsAccess
{
    public const ANY = '*';

    /** @var Access\Identity */
    private $identity;
    /** @var array<string, string[]> */
    private $roles = [];

    /**
     * @param array<string, string[]> $roles the roles granting each resource. A resource without an entry falls back
     *  to the roles of ANY, and is denied if there are none; an entry with no roles is denied.
     */
    public function __construct(Access\Identity $identity, array $roles)
    {
        $this->identity = $identity;
        $this->roles = $roles;
    }

    public function granted(string $resource): bool
    {
        $roles = $this->roles[$resource] ?? $this->roles[self::ANY] ?? [];

        return $roles !== [] && $this->identity->hasRole($roles);
    }
}
