<?php

namespace Virtue\Access\Identities;

use Virtue\Access;

class Root implements Access\Identity
{
    /** @var string */
    private $name = '';

    public function __construct(string $name = Access\Identity::Superuser)
    {
        $this->name = $name;
    }

    public function hasRole(array $roles): bool
    {
        return true;
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
        return $this->name;
    }
}