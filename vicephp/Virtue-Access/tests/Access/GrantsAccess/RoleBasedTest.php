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

    public function testDeniesResourceWithoutRoles()
    {
        $access = new RoleBased(
            new Access\Identities\User('anUser', ['aRole']), ['aResource' => ['aRole'], 'emptyResource' => []]
        );

        $this->assertFalse($access->granted('unknownResource'), 'User was granted a resource without roles.');
        $this->assertFalse($access->granted('emptyResource'), 'User was granted a resource with an empty role list.');
    }

    public function testFallsBackToRolesOfAnyResource()
    {
        $roles = ['aResource' => ['aRole'], RoleBased::ANY => ['admin']];

        $admin = new RoleBased(new Access\Identities\User('anAdmin', ['admin']), $roles);
        $this->assertTrue($admin->granted('unknownResource'), 'Admin was denied a resource covered by ANY.');
        $this->assertFalse($admin->granted('aResource'), 'ANY was used although the resource has roles of its own.');

        $user = new RoleBased(new Access\Identities\User('anUser', ['aRole']), $roles);
        $this->assertFalse($user->granted('unknownResource'), 'User was granted a resource covered by ANY without its role.');
        $this->assertTrue($user->granted('aResource'), 'User was denied a resource despite having its role.');
    }

    public function testDeniesResourceWithEmptyRolesDespiteAny()
    {
        $access = new RoleBased(
            new Access\Identities\User('anAdmin', ['admin']), ['emptyResource' => [], RoleBased::ANY => ['admin']]
        );

        $this->assertFalse($access->granted('emptyResource'), 'An empty role list fell back to ANY.');
    }

    public function testDeniesRootResourceWithoutRoles()
    {
        $access = new RoleBased(new Access\Identities\Root(), ['aResource' => ['aRole']]);

        $this->assertTrue($access->granted('aResource'), 'Root was denied a configured resource.');
        $this->assertFalse($access->granted('unknownResource'), 'Root was granted a resource without roles.');
    }
}
