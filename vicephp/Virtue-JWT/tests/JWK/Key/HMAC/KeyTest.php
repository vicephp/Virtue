<?php

namespace Virtue\JWK\Key\HMAC;

use PHPUnit\Framework\TestCase;

class KeyTest extends TestCase
{
    public function testCreateKey()
    {
        $key = new Key('HS256', 'secret');
        $this->assertEquals('HS256', $key->alg());
        $this->assertEquals('secret', $key->secret());
    }
}
