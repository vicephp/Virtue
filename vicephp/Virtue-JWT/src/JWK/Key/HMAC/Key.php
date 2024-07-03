<?php

namespace Virtue\JWK\Key\HMAC;

use Virtue\JWK\SymmetricKey;

/** @phpstan-import-type Alg from \Virtue\JWT\Algorithm */
class Key implements SymmetricKey
{
    /** @var \Virtue\JWT\Algorithm::HS* */
    private $alg;
    /** @var string */
    private $secret;

    /** @param \Virtue\JWT\Algorithm::HS* $alg */
    public function __construct(string $alg, string $secret)
    {
        $this->alg = $alg;
        $this->secret = $secret;
    }

    /** @return Alg */
    public function alg(): string
    {
        return $this->alg;
    }

    public function secret(): string
    {
        return $this->secret;
    }
}
