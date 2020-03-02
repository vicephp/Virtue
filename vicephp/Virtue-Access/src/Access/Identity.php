<?php

namespace Virtue\Access;

interface Identity
{
    const Superuser = 'root';
    const Guest = 'guest';

    public function hasRole(array $roles): bool;
    public function isAuthenticated(): bool;
    public function getName(): string;
}
