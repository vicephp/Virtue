<?php

namespace Virtue\JWK\Key\ECDSA;

use Virtue\Encoding\ASN1;
use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Base64Url;

class PublicKey implements AsymmetricKey
{
    /** @var string */
    private $crv;
    /** @var string */
    private $x;
    /** @var string */
    private $y;
    /** @var string */
    private $id;

    private const CURVE_OID = [
        'P-256' => '1.2.840.10045.3.1.7',
        'P-256K' => '1.3.132.0.10',
        'P-384' => '1.3.132.0.34',
        'P-521' => '1.3.132.0.35',
    ];

    private const ALGS = [
        'P-256' => 'ES256',
        'P-256K' => 'ES256K',
        'P-384' => 'ES384',
        'P-521' => 'ES512',
    ];

    private const POINT_COORD_BIT_SIZE = [
        'P-256' => 32,
        'P-256K' => 32,
        'P-384' => 48,
        'P-521' => 66,
    ];

    public function __construct(string $id, string $crv, string $x, string $y)
    {
        assert(key_exists($crv, self::CURVE_OID), "{$crv} is not supported");
        $this->crv = $crv;
        $this->x = $x;
        $this->y = $y;
        $this->id = $id;
    }

    /** @return mixed[] */
    public function jsonSerialize(): array
    {
        throw new \RuntimeException(__METHOD__ . ' is not implemented');
    }

    public function asPem(): string
    {
        $x = str_pad(Base64Url::decode($this->x), self::POINT_COORD_BIT_SIZE[$this->crv], "\00", STR_PAD_LEFT);
        $y = str_pad(Base64Url::decode($this->y), self::POINT_COORD_BIT_SIZE[$this->crv], "\00", STR_PAD_LEFT);
        return "-----BEGIN PUBLIC KEY-----\n" .
            \chunk_split(ASN1::seq(
                ASN1::seq(
                    ASN1::oid("1.2.840.10045.2.1"),
                    ASN1::oid(self::CURVE_OID[$this->crv]),
                ),
                ASN1::bitstr("\x04{$x}{$y}"),
            )->__toString(), 64) .
            "-----END PUBLIC KEY-----";
    }

    public function alg(): string
    {
        return self::ALGS[$this->crv];
    }

    public function passphrase(): string
    {
        throw new \RuntimeException(__METHOD__ . ' is not implemented');
    }
}
