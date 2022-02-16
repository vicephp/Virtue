<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Http\OAuthKeysStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OAuth implements VerifiesToken
{
    private $keysStore;
    private $claimsVerifier;

    public function __construct(OAuthKeysStore $keysStore, ClaimsVerify $claimsVerifier)
    {
        $this->keysStore = $keysStore;
        $this->claimsVerifier = $claimsVerifier;
    }

    public function verify(Token $token): void
    {
        $this->claimsVerifier->verify($token);

        $keys = $this->keysStore->get($token->payload('iss'));

        (new JWKS($keys))->verify($token);
    }
}
