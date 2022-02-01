<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Algorithm;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class OpenSSLVerify extends Algorithm implements VerifiesToken
{
    private $public;

    private $supported = [
       'RS256' => OPENSSL_ALGO_SHA256,
       'RS384' => OPENSSL_ALGO_SHA384,
       'RS512' => OPENSSL_ALGO_SHA512,
    ];

    public function __construct(
        string $name,
        string $public
    )
    {
        parent::__construct($name);
        $this->public = $public;
    }

    public function verify(string $msg, string $sig): void
    {
        if(! $public = \openssl_pkey_get_public($this->public)) {
            throw new VerificationFailed('Key is invalid.');
        }
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
