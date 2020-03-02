<?php

namespace Virtue\Access\Identities;

use Virtue\Access;

class User implements Access\Identity
{
    /** @var string */
    private $name = '';
    /** @var array */
    private $roles = [];

    public function __construct(string $name = Access\Identity::Guest, array $roles = [])
    {
        $this->name = $name;
        $this->roles = array_merge([$name], $roles);
    }

    public function hasRole(array $roles): bool
    {
        return empty($roles) || !empty(array_intersect($roles, $this->roles));
    }

    public function isAuthenticated(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return  $this->name;
    }
}