<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWT\SignFailed;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class OpenSSLTest extends TestCase
{
    public function testSign()
    {
        $sslSign = new OpenSSLSign('RS256', 'file://'. __DIR__ .'/key.pem');
        $sslVerify = new OpenSSLVerify('RS256', 'file://'. __DIR__ .'/public.pem');
        $token = (new Token([], []))->signWith($sslSign);
        $sslVerify->verify($token);
        $this->assertTrue(true);
    }

    public function testSignFailed()
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Key or passphrase are invalid.');
        $sslVerify = new OpenSSLSign('RS256', 'some-invalid-key');
        $sslVerify->sign('a-message');
    }

    public function testVerificationFailed()
    {
        $key = \openssl_pkey_new();
        $private = '';
        \openssl_pkey_export($key, $private);

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Could not verify signature.');
        $sslVerify = new OpenSSLVerify('RS256', 'file://'. __DIR__ .'/public.pem');
        $token = (new Token([], []))->signWith(new OpenSSLSign('RS256', $private));
        $sslVerify->verify($token);
    }

    public function testKeyIsInvalid()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Key is invalid.');
        $sslVerify = new OpenSSLVerify('RS256', 'some-invalid-key');
        $sslVerify->verify(new Token([], []));
    }
}
