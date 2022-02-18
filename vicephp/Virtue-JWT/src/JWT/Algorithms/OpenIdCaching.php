<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeyCachingStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class OpenIdCaching implements VerifiesToken
{
    private $keyStore;
    private $openId;

    public function __construct(KeyCachingStore $keyStore, ClaimsVerify $claimsVerify)
    {
        $this->keyStore = $keyStore;
        $this->openId = new OpenId($keyStore, $claimsVerify);
    }

    public function verify(Token $token): void
    {
        try {
            $this->openId->verify($token);
        } catch (\Throwable $throwable) {
            // One more try with freshly loaded KeySet
            $this->keyStore->refresh($token);
            $this->openId->verify($token);
        }
    }
}
