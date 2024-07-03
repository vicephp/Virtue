<?php

namespace Virtue\JWK\Key\RSA;

use Virtue\JWK\AsymmetricKey;
use Virtue\JWT\Base64Url;

/** @phpstan-import-type Alg from \Virtue\JWT\Algorithm */
class PublicKey implements AsymmetricKey
{
    /** @var Alg */
    private $alg;
    /** @var string */
    private $modulus;
    /** @var string */
    private $exponent;

    public function __construct(string $alg, string $modulus, string $exponent)
    {
        $this->alg = $alg;
        $this->modulus = $modulus;
        $this->exponent = $exponent;
    }

    public function alg(): string
    {
        return $this->alg;
    }

    public function asPem(): string
    {
        $modulus = "\x00" . Base64Url::decode($this->modulus);
        $exponent = Base64Url::decode($this->exponent);

        $components = [
            'modulus'  => \pack('Ca*a*', 2, $this->encodeLength(\strlen($modulus)), $modulus),
            'exponent' => \pack('Ca*a*', 2, $this->encodeLength(\strlen($exponent)), $exponent),
        ];

        $length = $this->encodeLength(\strlen($components['modulus']) + \strlen($components['exponent']));
        $publicKey = \pack('Ca*a*a*', 48, $length, $components['modulus'], $components['exponent']);

        $rsaOID = \pack('H*', '300d06092a864886f70d0101010500'); // http://tools.ietf.org/html/rfc3279#section-2.3.1
        $publicKey = "\x00" . $publicKey;
        $publicKey = "\x03" . $this->encodeLength(\strlen($publicKey)) . $publicKey;

        $publicKey = \pack(
            'Ca*a*',
            48,
            $this->encodeLength(\strlen($rsaOID . $publicKey)),
            $rsaOID . $publicKey
        );

        return "-----BEGIN PUBLIC KEY-----\n" .
            \chunk_split(\base64_encode($publicKey), 64) .
            "-----END PUBLIC KEY-----";
    }

    private function encodeLength(int $length): string
    {
        if ($length <= 0x7F) {
            return \chr($length);
        }

        $temp = ltrim(\pack('N', $length), "\x00");

        return \pack('Ca*', 0x80 | \strlen($temp), $temp);
    }

    /**
     * @return array{ kty: \Virtue\JWK\KeyType::*, alg: Alg, n: string, e: string }
     */
    public function jsonSerialize(): array
    {
        return [
            'kty' => 'RSA',
            'alg' => $this->alg,
            'n'   => $this->modulus,
            'e'   => $this->exponent,
        ];
    }
}
