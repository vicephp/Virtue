<?php

namespace Virtue\JWK\Key\RSA;

use Virtue\Encoding\ASN1;
use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Base64Url;

/** @phpstan-import-type Key from \Virtue\JWK\KeySet
 * @phpstan-import-type Alg from \Virtue\JWT\Algorithm
 */
class PublicKey implements AsymmetricKey
{
    /** @var string */
    private $id;
    /** @var Alg */
    private $alg;
    /** @var string */
    private $modulus;
    /** @var string */
    private $exponent;

    public function __construct(string $id, string $alg, string $modulus, string $exponent)
    {
        $this->id = $id;
        $this->alg = $alg;
        $this->modulus = $modulus;
        $this->exponent = $exponent;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function asPem(): string
    {

        return "-----BEGIN PUBLIC KEY-----\n" .
            \chunk_split(ASN1::seq(
                ASN1::seq(
                    ASN1::oid('1.2.840.113549.1.1.1'),
                    ASN1::null(),
                ),
                ASN1::bitstr(
                    ASN1::seq(
                        ASN1::uint(Base64Url::decode($this->modulus)),
                        ASN1::uint(Base64Url::decode($this->exponent)),
                    )->encode()
                )
            )->__toString(), 64) .
            "-----END PUBLIC KEY-----";
    }

    /** @return Key */
    public function jsonSerialize(): array
    {
        return [
            'kty' => 'RSA',
            'kid' => $this->id,
            'alg' => $this->alg,
            'n'   => $this->modulus,
            'e'   => $this->exponent,
        ];
    }

    public function passphrase(): string
    {
        throw new \RuntimeException(__METHOD__ . ' is not implemented');
    }
}
