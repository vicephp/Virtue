<?php

namespace JWK\Key\RSA;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PublicKey;

class PublicKeyTest extends TestCase
{
    public function testCreateKey()
    {
        $key = new PublicKey('key-id', 'RS256', 'modulus', 'exponent');
        $this->assertEquals('key-id', $key->id());
        $this->assertEquals('RS256', $key->alg());
        $this->assertEquals(
            ['kty' => 'RSA', 'kid' => 'key-id', 'alg' => 'RS256', 'n' => 'modulus', 'e' => 'exponent'],
            $key->jsonSerialize()
        );
    }

    public function testAsPem()
    {
        $key = new PublicKey('key-id', 'RS256', 'modulus', 'exponent');
        $this->assertStringStartsWith('-----BEGIN PUBLIC KEY-----', $key->asPem());
        $this->assertStringEndsWith('-----END PUBLIC KEY-----', $key->asPem());
    }
}
