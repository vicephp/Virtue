<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWT\Base64Url;
use Virtue\JWT\SignFailed;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Mockery as M;

class OpenSSLTest extends TestCase
{
    public function testSign()
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
            Base64Url::encode($details['rsa']['e'])
        );

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);
        $token = (new Token([], []))->signWith($sslSign);
        $sslVerify->verify($token);
        $this->addToAssertionCount(1);
    }

    public function testSignFailed()
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Key or passphrase are invalid.');

        $private = new PrivateKey('RS256', 'invalid pem');

        $sslVerify = new OpenSSLSign($private);
        $sslVerify->sign('a-message');
    }

    public function testVerificationFailed()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Could not verify signature.');

        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);
        $private = new PrivateKey('RS256', $private);
        $public = new PublicKey('key-1', 'RS256', 'wrong', 'wrong');

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);

        $token = (new Token([], []))->signWith($sslSign);
        $sslVerify->verify($token);
    }

    public function testKeyIsInvalid()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Key is invalid.');

        $public = M::mock(PublicKey::class);
        $public->shouldReceive('alg')->andReturn('RS256')->once();
        $public->shouldReceive('asPem')->andReturn('invalid pem')->once();

        $sslVerify = new OpenSSLVerify($public);
        $sslVerify->verify(new Token([], []));
    }

    public function testAlgorithmNotSupportedBySigner()
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Algorithm EC is not supported');

        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);
        $private = new PrivateKey('EC', $private);

        $sslVerify = new OpenSSLSign($private);
        $sslVerify->sign(new Token([], []));
    }

    public function testAlgorithmNotSupportedByVerifier()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Algorithm EC is not supported');

        $public = new PublicKey('key-1', 'EC', 'modules', 'exponent');

        $sslVerify = new OpenSSLVerify($public);
        $sslVerify->verify(new Token([], []));
    }
}
