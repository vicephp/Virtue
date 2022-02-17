<?php

namespace Virtue\JWK\Key\RSA;

use Virtue\JWK\AsymmetricKey;

class PrivateKey implements AsymmetricKey
{
    /** @var string */
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

    public function jsonSerialize(): array
    {
        return [
            'alg' => $this->alg,
            'pem' => $this->pem,
        ];
    }
}
