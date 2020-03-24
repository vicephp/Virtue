<?php

namespace Virtue\Access\GrantsAccess\VoteBased;

use PHPUnit\Framework\TestCase;

class DecideAffirmativeTest extends TestCase
{
    public function testAnyGranted()
    {
        $access = new DecideAffirmative(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertTrue($access->granted('aResource'), 'User was denied access despite one voter granted access.');
    }

    public function testOnlyDenied()
    {
        $access = new DecideAffirmative(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
            ]
        );

        $this->assertFalse($access->granted('aResource'), 'User was granted access despite no voter granted access.');
    }
}
