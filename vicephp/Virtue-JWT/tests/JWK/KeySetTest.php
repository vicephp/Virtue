<?php

namespace Virtue\JWK;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PublicKey;

/**
 * @phpstan-import-type Key from KeySet
 */
class KeySetTest extends TestCase
{
    private const KEYS = [
        [
            'use' => 'sig',
            'kty' => 'RSA',
            'alg' => 'RS256',
            'kid' => 'rsa-key',
            'n' => 'modulus',
            'e' => 'exponent'
        ],
        [
            'use' => 'sig',
            'kty' => 'EC',
            'crv' => 'P-256',
            'alg' => 'ES256',
            'kid' => 'ec256-key',
            'x' => 'point-x',
            'y' => 'point-y'
        ],
        [
            'use' => 'sig',
            'kty' => 'EC',
            'alg' => 'ES384',
            'crv' => 'P-384',
            'kid' => 'ec384-key',
            'x' => 'point-x',
            'y' => 'point-y'
        ],
        [
            'use' => 'sig',
            'kty' => 'EC',
            'alg' => 'ES512',
            'crv' => 'P-521',
            'kid' => 'ec521-key',
            'x' => 'point-x',
            'y' => 'point-y'
        ],
        [
            'use' => 'sig',
            'kty' => 'OKP',
            'alg' => 'EdDSA',
            'crv' => 'Ed25519',
            'kid' => 'ed25519-key',
            'x' => 'public-key',
        ],
        [
            'use' => 'sig',
            'kty' => 'OKP',
            'alg' => 'EdDSA',
            'crv' => 'Ed448',
            'kid' => 'ed448-key',
            'x' => 'public-key',
        ],
    ];

    public function testSerialize(): void
    {
        $keySet = KeySet::fromArray(self::KEYS);
        $this->assertCount(count(self::KEYS), $keySet->getKeys());
        $this->assertEquals(self::KEYS, $keySet->jsonSerialize());
    }

    public function keys(): \Generator
    {
        yield [
            'rsa-key',
            'MCQwDQYJKoZIhvcNAQEBBQADEwAwEAIGAJqHbpbrAgZ7Gmid6e0=',
        ];
        yield [
            'ec256-key',
            'MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
            'AAAAAAAApoint+wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACmiKe37A==',
        ];
        yield [
            'ec384-key',
            'MHYwEAYHKoZIzj0CAQYFK4EEACIDYgAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
            'AAAAAAAAAAAAAAAAAAAAAAAAAKaIp7fsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
            'AAAAAAAAAAAAAAAAAAAAAAAAAKaIp7fs',
        ];
        yield [
            'ec521-key',
            'MIGbMBAGByqGSM49AgEGBSuBBAAjA4GGAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
            'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAApoint+wAAAAA' .
            'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
            'AAAAAAAAAAAApoint+w=',
        ];
        yield [
            'ed25519-key',
            'MBEwBQYDK2VwAwgApublic+kew==',
        ];
        yield [
            'ed448-key',
            'MBEwBQYDK2VxAwgApublic+kew==',
        ];
    }

    /** @dataProvider keys */
    public function testFromArray(string $kid, string $pem): void
    {
        $keySet = KeySet::fromArray(self::KEYS);
        $this->assertEquals($kid, $keySet->getKey($kid)->id());
        $this->assertEquals(
            "-----BEGIN PUBLIC KEY-----\n" . \chunk_split($pem, 64) . "-----END PUBLIC KEY-----",
            $keySet->getKey($kid)->asPem()
        );
    }

    public function testAddKey(): void
    {
        $keySet = new KeySet();
        $this->assertEmpty($keySet->getKeys());
        $keySet->addKey(new PublicKey('id', 'RS256', 'modulus', 'exponent'));
        $this->assertCount(1, $keySet->getKeys());
    }

    /**
     * @dataProvider invalidData
     * @param Key[] $keys
     */
    public function testInvalidData(array $keys): void
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
