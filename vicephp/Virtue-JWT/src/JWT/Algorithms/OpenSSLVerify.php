<?php

namespace Virtue\JWT\Algorithms;

use Virtue\Encoding\ASN1;
use Virtue\JWK\AsymmetricKey;
use Virtue\JWK\Key\EdDSA;
use Virtue\JWT\Algorithm;
use Virtue\JWT\Base64Url;
use Virtue\JWT\OpenSslException;
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
        'ES256' => OPENSSL_ALGO_SHA256,
        'ES256K' => OPENSSL_ALGO_SHA256,
        'ES384' => OPENSSL_ALGO_SHA384,
        'ES512' => OPENSSL_ALGO_SHA512,
        'EdDSA' => 0,
    ];

    public function __construct(AsymmetricKey $public)
    {
        parent::__construct($public->alg());
        $this->public = $public;
    }

    public function verify(Token $token): void
    {
        $alg = $token->headers('alg', 'none');
        $msg = $token->withoutSig();
        $sig = $token->signature();

        if ($alg == 'EdDSA' && $this->public->alg() == 'EdDSA' && !defined('OPENSSL_KEYTYPE_ED25519')) {
            assert($this->public instanceof EdDSA\PublicKey);
            $pub = Base64Url::decode($this->public->publicKey());
            if (strlen($pub) != SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
                throw new VerificationFailed('Key is invalid.', VerificationFailed::ON_SIGNATURE);
            }
            if (strlen($sig) != SODIUM_CRYPTO_SIGN_BYTES) {
                throw new VerificationFailed('Invalid signature.', VerificationFailed::ON_SIGNATURE);
            }
            if (!\sodium_crypto_sign_verify_detached($sig, $msg, $pub)) {
                throw new VerificationFailed('Could not verify signature.', VerificationFailed::ON_SIGNATURE);
            }
            return;
        }

        assert(is_string($alg));
        if (!isset($this->supported[$alg])) {
            throw new VerificationFailed("Algorithm {$alg} is not supported", VerificationFailed::ON_SIGNATURE);
        }

        if (!$public = \openssl_pkey_get_public($this->public->asPem())) {
            $opensslException = new OpenSslException(OpenSslException::collectErrors());
            throw new VerificationFailed('Key is invalid.', VerificationFailed::ON_SIGNATURE, $opensslException);
        }
        $ecPadding = [
            'ES256' => 32,
            'ES384' => 48,
            'ES512' => 66,
        ];
        if (array_key_exists($alg, $ecPadding)) {
            $x = substr($sig, 0, $ecPadding[$alg]);
            $y = substr($sig, $ecPadding[$alg]);
            $sig = ASN1::seq(ASN1::uint(ltrim($x, "\00")), ASN1::uint(ltrim($y, "\00")));
            $sig = $sig->encode();
        }

        // returns 1 on success, 0 on failure, -1 on error.
        $success = \openssl_verify($msg, $sig, $public, $this->supported[$alg]);
        //TODO remove together with the support of PHP versions < 8.0
        if (version_compare(PHP_VERSION, '8.0.0') < 0) {
            \openssl_pkey_free($public);
        }
        if ($success === 1) {
            return;
        }

        $opensslException = new OpenSslException(OpenSslException::collectErrors());

        if ($success === 0) {
            throw new VerificationFailed(
                'Could not verify signature.',
                VerificationFailed::ON_SIGNATURE,
                $opensslException
            );
        }

        throw new VerificationFailed(
            'OpenSSL error occurred during signature verification.',
            VerificationFailed::ON_UNKNOWN,
            $opensslException
        );
    }
}
