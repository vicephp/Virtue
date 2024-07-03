<?php

namespace Virtue\JWK;

use OutOfBoundsException;
use Virtue\JWK\Key\RSA\PublicKey;
use Webmozart\Assert\Assert;

/**
 * @phpstan-import-type Alg from \Virtue\JWT\Algorithm
 * @phpstan-type KeySetEntry = array{
 *    use: KeyUse::*,
 *    kty: KeyType::*,
 *    alg: Alg,
 *    kid: string,
 *    n?: string,
 *    e?: string,
 *    d?: string,
 * }
 */
class KeySet implements \JsonSerializable
{
    /** @var PublicKey[] */
    private $keys = [];

    /** @var KeyType::*[] */
    private static $supportedKeyTypes = [KeyType::RSA];

    /**
     * @param PublicKey[] $keys
     */
    public function __construct(array $keys = [])
    {
        Assert::allIsInstanceOf($keys, PublicKey::class);
        foreach ($keys as $key) {
            $this->keys[$key->id()] = $key;
        }
    }

    public function getKey(string $id): PublicKey
    {
        if (!isset($this->keys[$id])) {
            throw new OutOfBoundsException("Kei with id $id not found in key set");
        }

        return $this->keys[$id];
    }

    /**
     * @return PublicKey[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    public function addKey(PublicKey $key): void
    {
        $this->keys[$key->id()] = $key;
    }

    /** @param array<string,mixed>[] $keys */
    public static function fromArray(array $keys): self
    {
        $keySet = [];
        foreach ($keys as $key) {
            if (!is_array($key)) {
                continue;
            }

            //Skip keys not intended for signing
            if (($key['use'] ?? '') !== KeyUse::Signature) {
                continue;
            }

            // Skip unsupported key types
            if (!in_array($key['kty'] ?? '', self::$supportedKeyTypes)) {
                continue;
            }

            // Skip keys with missing key id, exponent or modulus as well as private keys
            if (!isset($key['kid']) || !isset($key['n']) || !isset($key['e']) || isset($key['d'])) {
                continue;
            }

            Assert::string($key['kid'], 'Key ID must be a string');
            Assert::string($key['alg'], 'Algorithm must be a string');
            Assert::string($key['n'], 'Modulus must be a string');
            Assert::string($key['e'], 'Exponent must be a string');

            $keySet[] = new PublicKey($key['kid'], $key['alg'], $key['n'], $key['e']);
        }

        return new KeySet($keySet);
    }

    /** @return KeySetEntry[] */
    public function jsonSerialize(): array
    {
        $keys = [];
        foreach ($this->keys as $key) {
            /** @var KeySetEntry $entry */
            $entry = array_merge($key->jsonSerialize(), ['use' => 'sig']);
            $keys[] = $entry;
        }

        return $keys;
    }
}
