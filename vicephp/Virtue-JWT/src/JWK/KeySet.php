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
    /** @var array{use: KeyUse::*, key: PublicKey, kid: string}[] */
    private $keys = [];

    /** @var KeyType::*[] */
    private static $supportedKeyTypes = [KeyType::RSA];

    /**
     * @param KeyUse::* $for
     */
    public function getKey(string $id, string $for = KeyUse::Signature): PublicKey
    {
        foreach ($this->keys as $key) {
            if ($key['kid'] === $id && $key['use'] === $for) {
                return $key['key'];
            }
        }

        throw new OutOfBoundsException("Kei with id $id not found in key set");
    }

    /**
     * @return PublicKey[]
     */
    public function getKeys(): array
    {
        return array_column($this->keys, 'key', 'kid');
    }

    /** @param KeyUse::* $use */
    public function addKey(string $id, PublicKey $key, string $use = KeyUse::Signature): void
    {
        $this->keys[] = ['use' => $use, 'key' => $key, 'kid' => $id];
    }

    /** @param array<string,mixed>[] $keys */
    public static function fromArray(array $keys): self
    {
        $keySet = new self();
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


            $keySet->addKey(
                $key['kid'],
                new PublicKey($key['alg'], $key['n'], $key['e']),
                $key['use']
            );
        }

        return $keySet;
    }

    /** @return KeySetEntry[] */
    public function jsonSerialize(): array
    {
        $keys = [];
        foreach ($this->keys as $key) {
            /** @var KeySetEntry $entry */
            $entry = array_merge($key['key']->jsonSerialize(), ['use' => 'sig']);
            $keys[] = $entry;
        }

        return $keys;
    }
}
