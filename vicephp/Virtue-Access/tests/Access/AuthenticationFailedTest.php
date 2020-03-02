<?php

namespace Virtue\Access;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identities\Guest;

class AuthenticationFailedTest extends TestCase
{
    public function testException()
    {
        $authFailed = new AuthenticationFailed(new Guest('aGuest'));
        $this->assertEquals('Authentication failed (aGuest)', $authFailed->getMessage());
    }
}
