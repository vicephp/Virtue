<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OpenId implements VerifiesToken
{
    private $keyStore;
    private $claimsVerifier;

    public function __construct(KeyStore $keyStore, ClaimsVerify $claimsVerifier)
    {
        $this->keyStore = $keyStore;
        $this->claimsVerifier = $claimsVerifier;
    }

    public function verify(Token $token): void
    {
        $this->claimsVerifier->verify($token);
        $keySet = $this->keyStore->getFor($token);
        (new JWKS($keySet))->verify($token);
    }
}
