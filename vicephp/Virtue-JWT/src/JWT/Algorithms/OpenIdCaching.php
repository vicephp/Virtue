<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeyCachingStore;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class OpenIdCaching implements VerifiesToken
{
    /** @var KeyCachingStore */
    private $keyStore;

    /** @var OpenId */
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
        } catch (VerificationFailed $e) {
            // Only a key id we don't know yet is worth a retry: the issuer may have rotated its keys.
            // Any other failure is final, so invalid tokens cannot make us call the issuer on every request.
            if ($e->getCode() !== VerificationFailed::ON_KEY) {
                throw $e;
            }

            $this->keyStore->refresh($token);
            $this->openId->verify($token);
        }
    }
}
