<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Algorithm;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

/** @phpstan-import-type Alg from \Virtue\JWT\Algorithm */
class OpenSSLVerify extends Algorithm implements VerifiesToken
{
    /** @var AsymmetricKey */
    private $public;

    /** @var array<Alg,int|string> */
    private $supported = [
        'RS256' => OPENSSL_ALGO_SHA256,
        'RS384' => OPENSSL_ALGO_SHA384,
        'RS512' => OPENSSL_ALGO_SHA512,
    ];

    public function __construct(AsymmetricKey $public)
    {
        parent::__construct($public->alg());
        $this->public = $public;
    }

    public function verify(Token $token): void
    {
        $alg = $token->headers('alg', 'none');
        assert(is_string($alg));
        if (!isset($this->supported[$alg])) {
            throw new VerificationFailed("Algorithm {$alg} is not supported", VerificationFailed::ON_SIGNATURE);
        }

        if (!$public = \openssl_pkey_get_public($this->public->asPem())) {
            throw new VerificationFailed('Key is invalid.', VerificationFailed::ON_SIGNATURE);
        }

        $msg = $token->withoutSig();
        $sig = $token->signature();
        // returns 1 on success, 0 on failure, -1 on error.
        $success = \openssl_verify($msg, $sig, $public, $this->supported[$alg]);
        //TODO remove together with the support of PHP versions < 8.0
        if (version_compare(PHP_VERSION, '8.0.0') < 0) {
            \openssl_pkey_free($public);
        }
        if ($success === 1) {
            return;
        } elseif ($success === 0) {
            throw new VerificationFailed('Could not verify signature.', VerificationFailed::ON_SIGNATURE);
        }

        throw new VerificationFailed('OpenSSL error: ' . \openssl_error_string(), VerificationFailed::ON_SIGNATURE);
    }
}
