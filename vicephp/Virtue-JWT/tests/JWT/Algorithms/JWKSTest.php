<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWK\KeySet;
use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class JWKSTest extends TestCase
{
    public function testVerify()
    {
        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);
        $private = new PrivateKey('RS256', $private);

        $details = \openssl_pkey_get_details($key);
        $public = new PublicKey(
            'key-1',
            'RS256',
            Base64Url::encode($details['rsa']['n']),
            Base64Url::encode($details['rsa']['e']));
        $keySet = new KeySet([$public]);

        $token = new Token(['kid' => 'key-1'], []);
        $signed = $token->signWith(new OpenSSLSign($private));

        $signed->verifyWith(new JWKS($keySet));
        // Pass this test if no exception is thrown
        $this->addToAssertionCount(1);
    }

    public function testVerifyFailWhenNoKeyIsFound()
    {
        $token = new Token(['kid' => 'key-1'], []);

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('No key found for kid: key-1');

        $token->verifyWith(new JWKS(new KeySet()));
    }

    public function testVerifyFailWrongKey()
    {
        $this->expectException(VerificationFailed::class);

        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);

        $private = new PrivateKey('RS256', $private);
        $public = new PublicKey('key-1', 'RS256', 'wrong', 'wrong');
        $keySet = new KeySet([$public]);

        $token = new Token(['kid' => 'key-1'], []);
        $signed = $token->signWith(new OpenSSLSign($private));

        $signed->verifyWith(new JWKS($keySet));
    }
}
