<?php

namespace Virtue\JWK;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;

class KeySetTest extends TestCase
{
    public function testFromArray()
    {
        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $keySet = KeySet::fromArray([$key]);
        $this->assertCount(1, $keySet->getKeys());
        $this->assertEquals('key id', $keySet->getKey('key id')->id());
        $this->assertEquals([$key], $keySet->jsonSerialize());
    }

    public function testAddKey()
    {
        $keySet = new KeySet();
        $this->assertEmpty($keySet->getKeys());
        $keySet->addKey(new PublicKey('id', 'RS256', 'modulus', 'exponent'));
        $this->assertCount(1, $keySet->getKeys());
    }

    public function testAddInvalidKey()
    {
        $this->expectException(\InvalidArgumentException::class);

        new KeySet([new PrivateKey('RS256', 'pem')]);
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidData(array $keys)
    {
        $keySet = KeySet::fromArray($keys);
        $this->assertEmpty($keySet->getKeys());
    }

    public function invalidData(): \Generator
    {
        yield 'wrong use' => [
            ['use' => 'encrypt', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 1, 'n' => 'modulus', 'e' => 'exponent']
        ];

        yield 'unsupported type' => [
            ['use' => 'sig', 'kty' => 'EC', 'alg' => 'RS256', 'kid' => 1, 'n' => 'modulus', 'e' => 'exponent']
        ];

        yield 'missing kid' => [
            ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'n' => 'modulus', 'e' => 'exponent']
        ];

        yield 'missing modulus' => [
            ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 1, 'e' => 'exponent']
        ];

        yield 'missing exponent' => [
            ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 1, 'n' => 'modulus']
        ];

        yield 'private key' => [
            ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 1, 'n' => 'modulus', 'e' => 'exponent', 'd' => 'd'],
        ];
    }
}
