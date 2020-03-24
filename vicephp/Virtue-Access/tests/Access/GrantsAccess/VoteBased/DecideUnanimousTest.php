<?php

namespace Virtue\Access\GrantsAccess\VoteBased;

use PHPUnit\Framework\TestCase;

class DecideUnanimousTest extends TestCase
{
    public function testAnyDenied()
    {
        $access = new DecideUnanimous(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertFalse($access->granted('aResource'), 'User was granted access despite one voter denied access.');
    }

    public function testOnlyGranted()
    {
        $access = new DecideUnanimous(
            [
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertTrue($access->granted('aResource'),'User was granted access despite no voter denied access.');
    }
}

