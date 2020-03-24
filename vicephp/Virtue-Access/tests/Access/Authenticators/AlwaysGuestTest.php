<?php

namespace Virtue\Access\Authenticators;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identities\Guest;
use Virtue\Access\Login;

class AlwaysGuestTest extends TestCase
{
    public function testAuthenticate()
    {
        $authority = new AlwaysGuest();
        $credentials = [Login::Username => 'aUser', Login::Password => 'aPassword'];

        $this->assertInstanceOf(Guest::class, $authority->authenticate(new Login($credentials)));
    }
}
