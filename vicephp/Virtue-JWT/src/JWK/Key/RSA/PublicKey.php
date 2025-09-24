<?php

namespace Virtue\JWK\Key\RSA;

use Virtue\Encoding\ASN1;
use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Base64Url;
use Virtue\JWT\VerificationFailed;
use Webmozart\Assert\Assert;

/**
 * @phpstan-type RSAKey = array{
 *    kid: string,
 *    kty: 'RSA',
 *    alg: value-of<PublicKey::SUPPORTED>,
 *    n: string,
 *    e: string,
 *    d?: string,
 * }
 */
class PublicKey implements AsymmetricKey
{
    /** @var string */
    private $id;
    /** @var value-of<PublicKey::SUPPORTED> */
    private $alg;
    /** @var string */
    private $modulus;
    /** @var string */
    private $exponent;

    private const SUPPORTED = ['RS256', 'RS384', 'RS512'];

    /** @phpstan-assert-if-true value-of<PublicKey::SUPPORTED> $alg */
    private function isSupported(string $alg): bool
    {
        return in_array($alg, self::SUPPORTED);
    }

    public function __construct(string $id, string $alg, string $modulus, string $exponent)
    {
        assert($this->isSupported($alg), new VerificationFailed("Algorithm {$alg} is not supported"));
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

    /** @return RSAKey */
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

    public function withPassphrase(string $passphrase): void
    {
        throw new \RuntimeException(__METHOD__ . ' is not implemented');
    }
}
