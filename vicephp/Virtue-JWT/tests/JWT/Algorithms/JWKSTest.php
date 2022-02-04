<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class JWKSTest extends TestCase
{
    public function testVerify()
    {
        $key = \openssl_pkey_new();
        $details = \openssl_pkey_get_details($key);
        $jwks = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'kid' => 'key-1',
                    'use' => 'sig',
                    'alg' => 'RS256',
                    'n' => Base64Url::encode($details['rsa']['n']),
                    'e' => Base64Url::encode($details['rsa']['e']),
                ],
            ],
        ];

        $private = '';
        \openssl_pkey_export($key, $private);
        $rsa256 = new OpenSSLSign('RS256', $private);

        $token = new Token(['kid' => 'key-1'], []);

        $signed = $token->signWith($rsa256);

        $signed->verifyWith(new JWKS($jwks));
        // Pass this test if no exception is thrown
        $this->addToAssertionCount(1);
    }

    public function testVerifyFailWhenNoKeyIsFound()
    {
        $jwks = [
            'keys' => []
        ];

        $token = new Token(['kid' => 'key-1'], []);

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('No key found for kid: key-1');

        $token->verifyWith(new JWKS($jwks));
    }

    public function testVerifyFailWrongKey()
    {
        $jwks = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'kid' => 'key-1',
                    'use' => 'sig',
                    'alg' => 'RS256',
                    'n' => Base64Url::encode('wrong'),
                    'e' => Base64Url::encode('wrong'),
                ],
            ],
        ];

        $token = new Token(['kid' => 'key-1'], []);

        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);
        $rsa256 = new OpenSSLSign('RS256', $private);

        $signed = $token->signWith($rsa256);

        $this->expectException(VerificationFailed::class);

        $signed->verifyWith(new JWKS($jwks));
    }

    public function testVerifyUnsupportedKeyType()
    {
        $jwks = [
            'keys' => [
                [
                    'kty' => 'EC',
                    'kid' => 'key-1',
                    'use' => 'sig',
                    'alg' => 'ES256',
                    'crv' => 'P-256',
                    'x' => Base64Url::encode('wrong'),
                    'y' => Base64Url::encode('wrong'),
                ],
            ],
        ];

        $token = new Token(['kid' => 'key-1'], []);

        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);
        $es256 = new OpenSSLSign('RS256', $private);

        $signed = $token->signWith($es256);

        $this->expectException(VerificationFailed::class);

        $signed->verifyWith(new JWKS($jwks));
    }
}
