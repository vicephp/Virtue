<?php

namespace Virtue\JWT\Algorithms;

use Virtue\Encoding\ASN1;
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
        if (!defined('OPENSSL_KEYTYPE_ED25519') && $this->private->alg() == 'EdDSA') {
            // Last 32 bytes pf the DER-encoded private key is the seed
            $seed = \substr(\base64_decode(\str_replace(
                ["-----BEGIN PRIVATE KEY-----", "-----END PRIVATE KEY-----", "\n"],
                "",
                $this->private->asPem()
            )), -32);

            \assert($seed !== false);
            $secret = \sodium_crypto_sign_secretkey(\sodium_crypto_sign_seed_keypair($seed));
            return \sodium_crypto_sign_detached($msg, $secret);
        }

        if (!$private = \openssl_pkey_get_private($this->private->asPem(), $this->private->passphrase())) {
            throw new SignFailed('Key or passphrase are invalid.');
        }

        if (!isset($this->supported[$this->name])) {
            throw new SignFailed("Algorithm {$this->name} is not supported");
        }

        $signature = '';
        $success = \openssl_sign($msg, $signature, $private, $this->supported[$this->name]);

        Assert::string($signature);
        $ecPadding = [
            'ES256' => 32,
            'ES384' => 48,
            'ES512' => 66,
        ];
        if (array_key_exists($this->private->alg(), $ecPadding)) {
            $block = ASN1::decode($signature);
            assert($block->type() == ASN1::SEQUENCE);

            $block = ASN1::decode($block->bytes());
            assert($block->type() == ASN1::INTEGER);
            $x = str_pad(ltrim($block->bytes(), "\00"), $ecPadding[$this->private->alg()], "\00", STR_PAD_LEFT);

            $block = ASN1::decode($block->rest());
            assert($block->type() == ASN1::INTEGER);
            $y = str_pad(ltrim($block->bytes(), "\00"), $ecPadding[$this->private->alg()], "\00", STR_PAD_LEFT);
            $signature = $x . $y;
        }

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
