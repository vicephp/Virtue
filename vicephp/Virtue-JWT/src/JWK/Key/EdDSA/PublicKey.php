<?php

namespace Virtue\JWK\Key\EdDSA;

use Virtue\Encoding\ASN1;
use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Base64Url;

class PublicKey implements AsymmetricKey
{
    /** @var string */
    private $id;
    /** @var string */
    private $crv;
    /** @var string */
    private $public;

    private const CURVE_OID = [
        'Ed25519' => '1.3.101.112',
        'Ed448' => '1.3.101.113',
    ];


    public function __construct(string $id, string $crv, string $public)
    {
        assert(key_exists($crv, self::CURVE_OID), "Curve {$crv} is not supported");
        $this->id = $id;
        $this->crv = $crv;
        $this->public = $public;
    }

    /** @return mixed[] */
    public function jsonSerialize(): array
    {
        return [
            'kty' => 'OKP',
            'alg' => 'EdDSA',
            'kid' => $this->id,
            'crv' => $this->crv,
            'x' => $this->public,
        ];
    }

    public function asPem(): string
    {
        return "-----BEGIN PUBLIC KEY-----\n" .
            \chunk_split(ASN1::seq(
                ASN1::seq(
                    ASN1::oid(self::CURVE_OID[$this->crv]),
                ),
                ASN1::bitstr(Base64Url::decode($this->public)),
            )->__toString(), 64) .
            "-----END PUBLIC KEY-----";
    }

    public function alg(): string
    {
        return 'EdDSA';
    }

    public function passphrase(): string
    {
        throw new \Exception(__METHOD__ . ' is not implemented yet');
    }

    public function id(): string
    {
        return $this->id;
    }
}
