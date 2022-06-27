<?php

namespace Virtue\JWT;

use Webmozart\Assert\Assert;

class ChainOfVerifiers implements VerifiesToken
{
    /** @var VerifiesToken[] */
    private $verifiers;

    public function __construct(array $verifiers)
    {
        Assert::allIsInstanceOf($verifiers, VerifiesToken::class);
        $this->verifiers = $verifiers;
    }


    public function verify(Token $token): void
    {
        // once we switch to php7.4+, this code can be replaced with one line
        array_walk($this->verifiers, function (VerifiesToken $verifier) use ($token) {
            $verifier->verify($token);
        });
    }
}
