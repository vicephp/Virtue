<?php

namespace Virtue\Access\GrantsAccess;

use PHPUnit\Framework\TestCase;
use Virtue\Access;

class RoleBasedTest extends TestCase
{
    public function testHasRole()
    {
        $access = new RoleBased(
            new Access\Identities\User('anUser', ['aRole']), ['aResource' => ['aRole']]
        );

        $this->assertEquals(true, $access->granted('aResource'), 'User was denied access despite having role.');
    }

    public function testHasNotRole()
    {
        $access = new RoleBased(
            new Access\Identities\User('anUser', ['aRole']), ['aResource' => ['bRole']]
        );

        $this->assertEquals(false, $access->granted('aResource'), "User was granted access despite he hasn't role.");
    }
}
