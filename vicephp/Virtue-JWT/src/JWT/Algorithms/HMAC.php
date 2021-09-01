<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Algorithm;
use Virtue\JWT\SignsToken;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class HMAC extends Algorithm implements SignsToken, VerifiesToken
{
    private $secret;
    private $supported = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
    ];

    public function __construct(string $name, string $secret)
    {
        parent::__construct($name);
        $this->secret = $secret;
    }

    public function sign(string $msg): string
    {
        return \hash_hmac($this->supported[$this->name], $msg, $this->secret, true);
    }

    public function verify(string $msg, string $sig): void
    {
        if(\hash_equals($this->sign($msg), $sig)) {
            return;
        }

        throw new VerificationFailed();
    }
}
