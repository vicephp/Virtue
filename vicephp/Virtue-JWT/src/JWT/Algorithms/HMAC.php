<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\Key;
use Virtue\JWT\Algorithm;
use Virtue\JWT\SignsToken;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class HMAC extends Algorithm implements SignsToken, VerifiesToken
{
    /** @var \Virtue\JWK\Key\HMAC\Key */
    private $key;
    private $supported = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
    ];

    public function __construct(Key\HMAC\Key $key)
    {
        parent::__construct($key->alg());;

        if (!isset($this->supported[$this->name])) {
            throw new \OutOfBoundsException("Algorithm {$this->name} is not supported");
        }

        $this->key = $key;
    }

    public function sign(string $msg): string
    {
        return \hash_hmac($this->supported[$this->name], $msg, $this->key->secret(), true);
    }

    public function verify(Token $token): void
    {
        if (\hash_equals($this->sign($token->withoutSig()), $token->signature())) {
            return;
        }

        throw new VerificationFailed();
    }
}
