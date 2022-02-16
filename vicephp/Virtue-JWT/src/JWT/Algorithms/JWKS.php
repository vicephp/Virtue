<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class JWKS implements VerifiesToken
{
    /** @var VerifiesToken[] */
    private $verifiers = [];

    private $supportedKeyTypes = ['RSA'];

    public function __construct(array $jwks)
    {
        $keys = $jwks['keys'] ?? [];
        foreach ($keys as $key) {
            // Skip keys not intended for signing
            if (($key['use'] ?? '') !== 'sig') {
                continue;
            }

            // Skip unsupported key types
            if (!in_array($key['kty'] ?? '', $this->supportedKeyTypes)) {
                continue;
            }

            // Skip keys with missing key id. exponent or modulus as well as private keys.
            if (!isset($key['kid']) || !isset($key['n']) || !isset($key['e']) || isset($key['d'])) {
                continue;
            }
            $kid = $key['kid'];
            $pem = $this->getPemKey($key['n'], $key['e']);
            $alg = $key['alg'];

            $this->verifiers[$kid] = new OpenSSLVerify($alg, $pem);
        }
    }

    public function verify(Token $token): void
    {
        $kid = $token->header('kid');
        if (!isset($this->verifiers[$kid])) {
            throw new VerificationFailed('No key found for kid: ' . $kid);
        }
        $verifier = $this->verifiers[$kid];
        $verifier->verify($token);
    }

    /**
     * Convert raw modulus and exponent to PEM format
     */
    private function getPemKey(string $n, string $e): string
    {
        $modulus = "\x00" . Base64Url::decode($n);
        $exponent = Base64Url::decode($e);

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
}
