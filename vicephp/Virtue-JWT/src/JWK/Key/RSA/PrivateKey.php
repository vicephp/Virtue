<?php

namespace Virtue\JWK\Key\RSA;

use Virtue\JWK\AsymmetricKey;
use Virtue\JWK\KeyType;

/** @phpstan-import-type Alg from \Virtue\JWT\Algorithm */
class PrivateKey implements AsymmetricKey
{
    /** @var Alg */
    private $alg;
    /** @var string */
    private $pem;
    /** @var string */
    private $passphrase = '';

    public function __construct(string $alg, string $pem)
    {
        $this->alg = $alg;
        $this->pem = $pem;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function asPem(): string
    {
        return $this->pem;
    }

    public function withPassphrase(string $passphrase): void
    {
        $this->passphrase = $passphrase;
    }

    public function passphrase(): string
    {
        return $this->passphrase;
    }

    /** @return array{alg: Alg, kty: KeyType::*, pem: string} */
    public function jsonSerialize(): array
    {
        return [
            'alg' => $this->alg,
            'kty' => KeyType::RSA,
            'pem' => $this->pem,
        ];
    }
}
