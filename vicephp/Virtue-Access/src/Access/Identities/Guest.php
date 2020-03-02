<?php

namespace Virtue\Access\Identities;

use Virtue\Access;

class Guest implements Access\Identity
{
    /** @var string */
    private $name = '';

    public function __construct(string $name = Access\Identity::Guest)
    {
        $this->name = $name;
    }

    public function hasRole(array $roles): bool
    {
        return false;
    }

    public function isAuthenticated(): bool
    {
        return false;
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
