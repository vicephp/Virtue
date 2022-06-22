<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeySet;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class JWKS implements VerifiesToken
{
    /** @var VerifiesToken[] */
    private $verifiers = [];

    public function __construct(KeySet $keySet)
    {
        foreach ($keySet->getKeys() as $keyId => $key) {
            $this->verifiers[$keyId] = new OpenSSLVerify($key);
        }
    }

    public function verify(Token $token): void
    {
        $kid = $token->headers('kid');
        if (!isset($this->verifiers[$kid])) {
            throw new VerificationFailed('No key found for kid: ' . $kid);
        }
        $verifier = $this->verifiers[$kid];
        $verifier->verify($token);
    }
}
