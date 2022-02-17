<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWT\Algorithm;
use Virtue\JWT\SignFailed;
use Virtue\JWT\SignsToken;

class OpenSSLSign extends Algorithm implements SignsToken
{
    private $private;

    private $supported = [
        'RS256' => OPENSSL_ALGO_SHA256,
        'RS384' => OPENSSL_ALGO_SHA384,
        'RS512' => OPENSSL_ALGO_SHA512,
    ];

    public function __construct(PrivateKey $private)
    {
        parent::__construct($private->alg());
        $this->private = $private;
    }

    public function sign(string $msg): string
    {
        if (!$private = \openssl_pkey_get_private($this->private->asPem(), $this->private->passphrase())) {
            throw new SignFailed('Key or passphrase are invalid.');
        }

        if (!isset($this->supported[$this->name])) {
            throw new SignFailed("Algorithm {$this->name} is not supported");
        }

        $signature = '';
        $success = \openssl_sign($msg, $signature, $private, $this->supported[$this->name]);
        //TODO remove together with the support of PHP versions < 8.0
        if (version_compare(PHP_VERSION, '8.0.0') < 0) {
            \openssl_pkey_free($private);
        }
        if (!$success) {
            throw new SignFailed('OpenSSL error: ' . \openssl_error_string());
        } else {
            return $signature;
        }
    }
}
