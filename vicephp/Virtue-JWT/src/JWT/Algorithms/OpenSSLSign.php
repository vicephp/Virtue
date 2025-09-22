<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\AsymmetricKey;
use Virtue\JWK\Key\OpenSSL\Exportable;
use Virtue\JWT\Algorithm;
use Virtue\JWT\SignFailed;
use Virtue\JWT\SignsToken;
use Webmozart\Assert\Assert;

/** @phpstan-import-type Alg from \Virtue\JWT\Algorithm */
class OpenSSLSign extends Algorithm implements SignsToken
{
    /** @var Exportable */
    private $private;

    /** @var array<Alg,int|string> */
    private $supported = [
        'RS256' => OPENSSL_ALGO_SHA256,
        'RS384' => OPENSSL_ALGO_SHA384,
        'RS512' => OPENSSL_ALGO_SHA512,
        'ES256' => OPENSSL_ALGO_SHA256,
        'ES256K' => OPENSSL_ALGO_SHA256,
        'ES384' => OPENSSL_ALGO_SHA384,
        'ES512' => OPENSSL_ALGO_SHA512,
        'EdDSA' => 0,
    ];

    public function __construct(Exportable $private)
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
            Assert::string($signature);
            return $signature;
        }
    }
}
