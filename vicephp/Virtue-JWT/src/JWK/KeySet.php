<?php

namespace Virtue\JWK;

use _HumbugBox58fd4d9e2a25\OutOfBoundsException;
use Virtue\JWK\Key\RSA\PublicKey;
use Webmozart\Assert\Assert;

class KeySet implements \JsonSerializable
{
    /** @var PublicKey[] */
    private $keys = [];
    private static $supportedKeyTypes = ['RSA'];

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

    public static function fromArray(array $keys): self
    {
        $keySet = [];
        foreach ($keys as $key) {
            //Skip keys not intended for signing
            if (($key['use'] ?? '') !== 'sig') {
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

            $keySet[] = new PublicKey($key['kid'], $key['alg'], $key['n'], $key['e']);
        }

        return new KeySet($keySet);
    }

    public function jsonSerialize(): array
    {
        $keys = [];
        foreach ($this->keys as $key) {
            $keys[] = array_merge($key->jsonSerialize(), ['use' => 'sig']);
        }

        return $keys;
    }
}
