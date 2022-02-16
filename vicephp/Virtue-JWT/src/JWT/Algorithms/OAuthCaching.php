<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Http\OAuthCachingKeysStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OAuthCaching implements VerifiesToken
{
    private $keysStore;
    private $claimsVerifier;

    public function __construct(OAuthCachingKeysStore $keysStore, ClaimsVerify $claimsVerifier)
    {
        $this->keysStore = $keysStore;
        $this->claimsVerifier = $claimsVerifier;
    }

    public function verify(Token $token): void
    {
        $this->claimsVerifier->verify($token);

        $keys = $this->keysStore->get($token->payload('iss'));

        try {
            (new JWKS($keys))->verify($token);
        } catch (\Throwable $throwable) {
            // One more try with freshly loaded keys
            $issuer = $token->payload('iss');
            $this->keysStore->remove($issuer);
            $keys = $this->keysStore->get($issuer);
            (new JWKS($keys))->verify($token);
        }
    }
}
