<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\KeySet;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;
use Webmozart\Assert\Assert;

class JWKS implements VerifiesToken
{
    /** @var VerifiesToken[] */
    private $verifiers = [];

    /** @var string[] */
    private $algorithms = [];

    public function __construct(KeySet $keySet)
    {
        foreach ($keySet->getKeys() as $keyId => $key) {
            $this->verifiers[$keyId] = new OpenSSLVerify($key);
            $this->algorithms[$keyId] = $key->alg();
        }
    }

    public function verify(Token $token): void
    {
        $kid = $token->headers('kid');
        Assert::string($kid);
        if (!isset($this->verifiers[$kid])) {
            throw new VerificationFailed('No key found for kid: ' . $kid, VerificationFailed::ON_KEY);
        }

        // The key decides the algorithm, never the token, to rule out algorithm confusion
        $alg = $token->headers('alg');
        if ($alg !== $this->algorithms[$kid]) {
            throw new VerificationFailed(
                sprintf("Algorithm '%s' does not match the key for kid: %s", is_string($alg) ? $alg : '', $kid),
                VerificationFailed::ON_ALGORITHM
            );
        }

        $this->verifiers[$kid]->verify($token);
    }
}
