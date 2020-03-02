<?php

namespace Virtue\Access\Identities;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsAlwaysAuthenticated()
    {
        $this->assertEquals(true, (new User('anUser'))->isAuthenticated());
    }

    public function testHasGivenRolesOnly()
    {
        $user = new User('anUser', ['aRole', 'bRole']);

        $this->assertEquals(true, $user->hasRole(['aRole']));
        $this->assertEquals(true, $user->hasRole(['bRole']));
        $this->assertEquals(true, $user->hasRole(['aRole', 'bRole']));
        $this->assertEquals(false, $user->hasRole(['cRole']));
    }

    public function testName()
    {
        $user = new User('anUser', ['aRole', 'bRole']);
        $this->assertEquals('anUser', $user->getName());
        $this->assertEquals('anUser', (string)$user);
    }
}
