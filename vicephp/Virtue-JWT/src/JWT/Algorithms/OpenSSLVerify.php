<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\Key;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWT\Algorithm;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class OpenSSLVerify extends Algorithm implements VerifiesToken
{
    private $publicKey;

    private $supported = [
        'RS256' => OPENSSL_ALGO_SHA256,
        'RS384' => OPENSSL_ALGO_SHA384,
        'RS512' => OPENSSL_ALGO_SHA512,
    ];

    public function __construct(PublicKey $publicKey)
    {
        parent::__construct($publicKey->alg());
        $this->publicKey = $publicKey;
    }

    public function verify(Token $token): void
    {
        if (!isset($this->supported[$this->name])) {
            throw new VerificationFailed("Algorithm {$this->name} is not supported");
        }

        if (!$public = \openssl_pkey_get_public($this->publicKey->asPem())) {
            throw new VerificationFailed('Key is invalid.');
        }

        $msg = $token->withoutSig();
        $sig = $token->signature();
        // returns 1 on success, 0 on failure, -1 on error.
        $success = \openssl_verify($msg, $sig, $public, $this->supported[$this->name]);
        //TODO remove together with the support of PHP versions < 8.0
        if (version_compare(PHP_VERSION, '8.0.0') < 0) {
            \openssl_pkey_free($public);
        }
        if ($success === 1) {
            return;
        } elseif ($success === 0) {
            throw new VerificationFailed();
        }

        throw new VerificationFailed('OpenSSL error: ' . \openssl_error_string());
    }
}
