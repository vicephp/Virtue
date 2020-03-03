<?php

namespace Virtue\Access\GrantsAccess;

use PHPUnit\Framework\TestCase;

class GrantsAccessTest extends TestCase
{
    public function testAlwaysDenied()
    {
        $access = new AlwaysDenied();

        $this->assertFalse($access->granted('aResource'), 'Access was granted despite it must be denied.');
    }

    public function testAlwaysGranted()
    {
        $access = new AlwaysGranted();

        $this->assertTrue($access->granted('aResource'), 'Access was denied despite it must be always granted.');
    }
}
