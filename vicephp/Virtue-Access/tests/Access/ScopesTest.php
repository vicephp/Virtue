<?php

namespace Virtue\Access;

use PHPUnit\Framework\TestCase;

class ScopesTest extends TestCase
{
    public function testResource()
    {
        $this->assertSame('scopes/read-catalog', Scopes::resource('read-catalog'));
    }

    public function testAsRoles()
    {
        $this->assertSame(['scope:read-catalog', 'scope:create-basket'], Scopes::asRoles(['read-catalog', 'create-basket']));
        $this->assertSame([], Scopes::asRoles([]));
    }

    public function testGrants()
    {
        $this->assertSame(
            ['scopes/read-catalog' => ['scope:read-catalog'], 'scopes/create-basket' => ['scope:create-basket']],
            Scopes::grants(['read-catalog', 'create-basket'])
        );
    }

    public function testGrantsResourceOfOwnScopes()
    {
        $access = new GrantsAccess\RoleBased(
            new Identities\User('aClient', Scopes::asRoles(['read-catalog'])),
            Scopes::grants(['read-catalog', 'create-basket'])
        );

        $this->assertTrue($access->granted(Scopes::resource('read-catalog')));
        $this->assertFalse($access->granted(Scopes::resource('create-basket')));
    }

    public function testDoesNotGrantScopeToIdentityNamedLikeIt()
    {
        // Identities\User counts its name as a role
        $access = new GrantsAccess\RoleBased(
            new Identities\User('create-basket', Scopes::asRoles([])),
            Scopes::grants(['create-basket'])
        );

        $this->assertFalse($access->granted(Scopes::resource('create-basket')));
    }
}
