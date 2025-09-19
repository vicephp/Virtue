<?php

namespace Virtue\JWK\Key\EdDSA;

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

    /** @return mixed[] */
    public function jsonSerialize(): array
    {
        throw new \RuntimeException(__METHOD__ . ' is not implemented');
    }

    public function asPem(): string
    {
        return $this->pem;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function withPassphrase(string $passphrase): void
    {
        $this->passphrase = $passphrase;
    }

    public function passphrase(): string
    {
        return $this->passphrase;
    }

    public function id(): string
    {
        throw new \Exception(__METHOD__ . ' is not implemented yet');
    }
}
