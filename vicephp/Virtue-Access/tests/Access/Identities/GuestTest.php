<?php

namespace Virtue\Access\Identities;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identity;

class GuestTest extends TestCase
{
    public function testIsNeverAuthenticated()
    {
        $this->assertFalse((new Guest())->isAuthenticated());
    }

    public function testNeverHasRole()
    {
        $root = new Guest();
        $this->assertFalse($root->hasRole(['aRole']), "Guest has role despite it must not");
    }

    public function testDefaultNameIsGuest()
    {
        $guest = new Guest();
        $this->assertEquals(Identity::Guest, $guest->getName(), "Guest has name {$guest->getName()}, must be guest.");
        $this->assertEquals(Identity::Guest, (string)$guest);
    }
}
