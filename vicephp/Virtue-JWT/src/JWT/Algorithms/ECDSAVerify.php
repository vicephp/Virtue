<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWK\Key\ECDSA\PublicKey;
use Virtue\JWT\Algorithm;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class ECDSAVerify extends Algorithm implements VerifiesToken
{
    private $supported = [
        'ES256' => OPENSSL_ALGO_SHA256,
        'ES384' => OPENSSL_ALGO_SHA384,
        'ES512' => OPENSSL_ALGO_SHA512,
    ];

    private const DER_SEQ = 0x30;
    private const DER_INT = 0x02;

    /** @var PublicKey */
    private $public;

    public function __construct(PublicKey $key)
    {
        parent::__construct($key->alg());
        $this->public = $key;
    }

    /**
     * @inheritDoc
     */
    public function verify(\Virtue\JWT\Token $token): void
    {
        if (!isset($this->supported[$this->name])) {
            throw new VerificationFailed("Algorithm {$this->name} is not supported");
        }

        if (!$public = \openssl_pkey_get_public($this->public->asPem())) {
            throw new VerificationFailed('Key is invalid.');
        }

        $msg = $token->withoutSig();
        $sig = $token->signature();
        $der = join(array_map(
            function ($v) {
                $str = rtrim(ord($v[0]) > 0x7f ? "\x00$v" : $v, "\x00");
                return pack('CCa*', self::DER_INT, strlen($str), $str);
            },
            str_split($sig, max(1, intdiv(strlen($sig), 2)))
        ));

        $success = openssl_verify(
            $msg,
            pack('CCa*', self::DER_SEQ, strlen($der), $der),
            $public,
            $this->supported[$this->name]
        );

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
