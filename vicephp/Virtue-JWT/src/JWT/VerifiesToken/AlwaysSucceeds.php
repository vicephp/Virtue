<?php

namespace Virtue\JWT\VerifiesToken;

use Virtue\JWT\Token;
use Virtue\JWT\VerifiesToken;

class AlwaysSucceeds implements VerifiesToken
{
    public function verify(Token $token): void
    {
        // do nothing
    }
}
