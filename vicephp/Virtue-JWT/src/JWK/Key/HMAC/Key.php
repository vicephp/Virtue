<?php

namespace Virtue\JWK\Key\HMAC;

use Virtue\JWK\SymmetricKey;

class Key implements SymmetricKey
{
    /** @var string */
    private $alg;
    /** @var string */
    private $secret;

    public function __construct(string $alg, string $secret)
    {
        $this->alg = $alg;
        $this->secret = $secret;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function secret(): string
    {
        return $this->secret;
    }
}
