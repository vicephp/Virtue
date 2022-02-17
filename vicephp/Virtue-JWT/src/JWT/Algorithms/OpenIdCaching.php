<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeyCachingStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OpenIdCaching implements VerifiesToken
{
    private $keysStore;
    private $openId;

    public function __construct(KeyCachingStore $keysStore, ClaimsVerify $claimsVerify)
    {
        $this->keysStore = $keysStore;
        $this->openId = new OpenId($keysStore, $claimsVerify);
    }

    public function verify(Token $token): void
    {
        try {
            $this->openId->verify($token);
        } catch (\Throwable $throwable) {
            // One more try with freshly loaded KeySet
            $this->keysStore->refresh($token);
            $this->openId->verify($token);
        }
    }
}
