<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OpenId implements VerifiesToken
{
    private $keysStore;
    private $claimsVerifier;

    public function __construct(KeyStore $keysStore, ClaimsVerify $claimsVerifier)
    {
        $this->keysStore = $keysStore;
        $this->claimsVerifier = $claimsVerifier;
    }

    public function verify(Token $token): void
    {
        $this->claimsVerifier->verify($token);

        $keySet = $this->keysStore->getFor($token);
        (new JWKS($keySet))->verify($token);
    }
}
