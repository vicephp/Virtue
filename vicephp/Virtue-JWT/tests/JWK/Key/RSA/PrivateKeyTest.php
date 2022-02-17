<?php

namespace Virtue\JWK\Key\RSA;

use PHPUnit\Framework\TestCase;

class PrivateKeyTest extends TestCase
{
    public function testCreateKey()
    {
        $key = new PrivateKey('RS256', 'pem');
        $this->assertEquals('RS256', $key->alg());
        $this->assertEquals('pem', $key->asPem());
        $this->assertEquals(['alg' => 'RS256', 'pem' => 'pem'], $key->jsonSerialize());
    }
}
