<?php

namespace Virtue\JWK;

use OutOfBoundsException;
use Virtue\JWK\Key\RSA;
use Virtue\JWK\Key\ECDSA;
use Virtue\JWK\Key\EdDSA;
use Webmozart\Assert\Assert;

/**
 * @phpstan-import-type RSAKey from RSA\PublicKey
 * @phpstan-import-type EdDSAKey from EdDSA\PublicKey
 * @phpstan-import-type ECDSAKey from ECDSA\PublicKey
 * @phpstan-type Key = RSAKey|ECDSAKey|EdDSAKey
 * @phpstan-type KeyType Key['kty']
 * @phpstan-type Alg Key['alg']
 * @phpstan-type KeyUse = 'sig'
 */
class KeySet implements \JsonSerializable
{
    /** @var AsymmetricKey[] */
    private $keys = [];

    /** @param AsymmetricKey[] $keys */
    public function __construct(array $keys = [])
    {
        Assert::allIsInstanceOf($keys, AsymmetricKey::class);
        foreach ($keys as $key) {
            $this->keys[$key->id()] = $key;
        }
    }

    public function getKey(string $id): AsymmetricKey
    {
        if (!isset($this->keys[$id])) {
            throw new OutOfBoundsException("Key with id $id not found in key set");
        }

        return $this->keys[$id];
    }

    /**
     * @return AsymmetricKey[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    public function addKey(AsymmetricKey $key): void
    {
        $this->keys[$key->id()] = $key;
    }

    /** @param array<string,mixed>[] $keys */
    public static function fromArray(array $keys): self
    {
        /** @var array<KeyType,(callable(string, mixed[]): ?AsymmetricKey)> */
        static $keyFactories = [];
        if (count($keyFactories) == 0) {
            $keyFactories['RSA'] = function (string $kid, array $key) {
                if (!isset($key['n']) || !isset($key['e']) || isset($key['d'])) {
                    return null;
                }

                Assert::string($key['alg']);
                Assert::string($key['n']);
                Assert::string($key['e']);

                return new RSA\PublicKey($kid, $key['alg'], $key['n'], $key['e']);
            };
            $keyFactories['EC'] = function (string $kid, array $key) {
                if (!isset($key['x']) || !isset($key['y']) || isset($key['d'])) {
                    return null;
                }

                Assert::string($key['crv']);
                Assert::string($key['x']);
                Assert::string($key['y']);

                return new ECDSA\PublicKey($kid, $key['crv'], $key['x'], $key['y']);
            };
            $keyFactories['OKP'] = function (string $kid, array $key) {
                if (!isset($key['x']) || isset($key['d'])) {
                    return null;
                }

                Assert::string($key['crv']);
                Assert::string($key['x']);

                return new EdDSA\PublicKey($kid, $key['crv'], $key['x']);
            };
        }

        $keySet = [];
        foreach ($keys as $key) {
            if (!is_array($key)) {
                continue;
            }

            //Skip keys not intended for signing
            if (($key['use'] ?? '') !== 'sig') {
                continue;
            }

            $kty = $key['kty'] ?? '';
            // Skip unsupported key types
            if (!is_string($kty) || !key_exists($kty, $keyFactories)) {
                continue;
            }

            $kid = $key['kid'] ?? null;
            if (!is_string($kid)) {
                continue;
            }

            if (($key = call_user_func($keyFactories[$kty], $kid, $key)) == null) {
                continue;
            }
            $keySet[] = $key;
        }

        return new KeySet($keySet);
    }

    /** @return Key[] */
    public function jsonSerialize(): array
    {
        /** @var Key[] */
        $keys = [];
        foreach ($this->keys as $key) {
            $key = $key->jsonSerialize();
            $key['use'] = 'sig';
            $keys[] = $key;
        }

        return $keys;
    }
}
