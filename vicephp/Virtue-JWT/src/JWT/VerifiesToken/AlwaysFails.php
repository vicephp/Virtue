<?php

namespace Virtue\JWT\VerifiesToken;

use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class AlwaysFails implements VerifiesToken
{
    /** @var string  */
    private $message;

    public function __construct(string $message = 'Could not verify token.')
    {
        $this->message = $message;
    }

    public function verify(Token $token): void
    {
        throw new VerificationFailed($this->message);
    }
}
