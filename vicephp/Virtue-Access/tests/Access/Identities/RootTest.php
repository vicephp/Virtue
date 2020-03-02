<?php

namespace Virtue\Access\Identities;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identity;

class RootTest extends TestCase
{
    public function testIsAlwaysAuthenticated()
    {
        $this->assertTrue((new Root())->isAuthenticated());
    }

    public function testAlwaysHasRole()
    {
        $root = new Root();
        $this->assertTrue($root->hasRole(['aRole']));
        $this->assertTrue($root->hasRole(['bRole']));
    }

    public function testDefaultNameIsSuperuser()
    {
        $root = new Root();
        $this->assertEquals(Identity::Superuser, $root->getName());
        $this->assertEquals(Identity::Superuser, (string)$root);
    }
}
