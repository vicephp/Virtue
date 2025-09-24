<?php

namespace Virtue\JWK\Key\OpenSSL;

use Virtue\JWK\AsymmetricKey;

/**
 * @phpstan-import-type Alg from \Virtue\JWT\Algorithm
 * @phpstan-type Key = array{
 *  alg: string,
 *  pem: string,
 * }
 */
class PrivateKey implements Exportable
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

    /** @return Key */
    public function jsonSerialize(): array
    {
        return [
            'alg' => $this->alg,
            'pem' => $this->pem,
        ];
    }

    public function id(): string
    {
        throw new \Exception(__METHOD__ . ' is not implemented yet');
    }
}
