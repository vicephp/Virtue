<?php

namespace Virtue\Access;

use PHPUnit\Framework\TestCase;
use Virtue\Access\Identities\Guest;

class AuthorizationFailedTest extends TestCase
{
    public function testException()
    {
        $authFailed = new AuthorizationFailed(new Guest('aGuest'), 'aResource');
        $this->assertEquals('Authorization failed (aGuest, aResource)', $authFailed->getMessage());
    }
}
