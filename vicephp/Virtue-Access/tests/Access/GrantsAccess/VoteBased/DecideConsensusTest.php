<?php

namespace Virtue\Access\GrantsAccess\VoteBased;

use PHPUnit\Framework\TestCase;

class DecideConsensusTest extends TestCase
{
    public function testEqual()
    {
        $access = new DecideConsensus(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertTrue($access->granted('aResource'), 'User was denied access despite votes were balanced.');
    }

    public function testMoreDenied()
    {
        $access = new DecideConsensus(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertFalse($access->granted('aResource'), 'User was granted access despite more voters denied access.');
    }

    public function testMoreGranted()
    {
        $access = new DecideConsensus(
            [
                new \Virtue\Access\GrantsAccess\AlwaysDenied(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
                new \Virtue\Access\GrantsAccess\AlwaysGranted(),
            ]
        );

        $this->assertTrue($access->granted('aResource'), 'User was denied access despite more voters granted access.');
    }
}
