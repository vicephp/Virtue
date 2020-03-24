<?php

namespace Virtue\Access\Authenticators;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identities\Root;
use Virtue\Access\Login;

class AlwaysRootTest extends TestCase
{
    public function testAuthenticate()
    {
        $authority = new AlwaysRoot();
        $credentials = [Login::Username => 'aUser', Login::Password => 'aPassword'];

        $this->assertInstanceOf(Root::class, $authority->authenticate(new Login($credentials)));
    }
}
