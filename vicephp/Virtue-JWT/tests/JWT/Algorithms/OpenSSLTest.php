<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA;
use Virtue\JWK\Key\ECDSA;
use Virtue\JWK\Key\EdDSA;
use Virtue\JWK\Key\OpenSSL;
use Virtue\JWT\Base64Url;
use Virtue\JWT\SignFailed;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Mockery as M;
use Webmozart\Assert\Assert;

class OpenSSLTest extends TestCase
{
    public function rsaAlgs(): \Generator
    {
        yield ['RS256'];
        yield ['RS384'];
        yield ['RS512'];
    }

    /** @dataProvider rsaAlgs */
    public function testSignRSA(string $alg): void
    {
        $key = \openssl_pkey_new();
        $this->assertNotFalse($key);
        $private = '';
        \openssl_pkey_export($key, $private);
        Assert::string($private);
        $private = new OpenSSL\PrivateKey($alg, $private);

        $details = \openssl_pkey_get_details($key);
        /* var_dump($details['key']); */
        $this->assertNotFalse($details);
        Assert::isMap($details['rsa']);
        Assert::string($details['rsa']['n']);
        Assert::string($details['rsa']['e']);
        $public = new RSA\PublicKey(
            'key-1',
            $alg,
            Base64Url::encode($details['rsa']['n']),
            Base64Url::encode($details['rsa']['e'])
        );

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);
        $token = (new Token([], []))->signWith($sslSign);
        $sslVerify->verify($token);
        $this->addToAssertionCount(1);
    }

    public function ecdsaAlgs(): \Generator
    {
        yield ['ES256', 'prime256v1', 'P-256'];
        yield ['ES256K', 'secp256k1', 'P-256K'];
        yield ['ES384', 'secp384r1', 'P-384'];
        yield ['ES512', 'secp521r1', 'P-521'];
    }

    /** @dataProvider ecdsaAlgs */
    public function testSignECDSA(string $alg, string $crvOpenSSL, string $crvJWK): void
    {
        $key = \openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => $crvOpenSSL,
        ]);
        $this->assertNotFalse($key);
        $private = '';
        \openssl_pkey_export($key, $private);
        Assert::string($private);
        $private = new OpenSSL\PrivateKey($alg, $private);

        $details = \openssl_pkey_get_details($key);
        $this->assertNotFalse($details);
        Assert::isMap($details['ec']);
        Assert::eq($crvOpenSSL, $details['ec']['curve_name']);
        Assert::string($details['ec']['x']);
        Assert::string($details['ec']['y']);
        $public = new ECDSA\PublicKey(
            'key-1',
            $crvJWK,
            Base64Url::encode($details['ec']['x']),
            Base64Url::encode($details['ec']['y'])
        );

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);
        $token = (new Token([], ['foo' => 'bar']))->signWith($sslSign);
        $sslVerify->verify($token);
        $this->addToAssertionCount(1);
    }

    public function eddsaAlgs(): \Generator
    {
        yield [
            'EdDSA',
            'MC4CAQAwBQYDK2VwBCIEIKqv2Nxz978STwRyqhsSCVk9svyqMoFjCldcRzfllCaM',
            'jWZ57Q7I6b9IMBD9Qekdh8kTPzeDuWqEu1NB24Njxfw',
            'Ed25519'
        ];
    }

    /** @dataProvider eddsaAlgs */
    public function testSignEdDSA(string $alg, string $secret, string $public, string $crvJWK): void
    {
        $private = "-----BEGIN PRIVATE KEY-----\n{$secret}\n-----END PRIVATE KEY-----";
        $private = new OpenSSL\PrivateKey($alg, $private);

        $public = new EdDSA\PublicKey('key-1', $crvJWK, $public);

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);
        $token = (new Token([], ['foo' => 'bar']))->signWith($sslSign);
        $sslVerify->verify($token);
        $this->addToAssertionCount(1);
    }

    public function testSignFailed(): void
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Key or passphrase are invalid.');

        $private = new OpenSSL\PrivateKey('RS256', 'invalid pem');

        $sslVerify = new OpenSSLSign($private);
        $sslVerify->sign('a-message');
    }

    public function testVerificationFailed(): void
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Could not verify signature.');

        $key = \openssl_pkey_new();
        $this->assertNotFalse($key);
        $private = '';
        \openssl_pkey_export($key, $private);
        Assert::string($private);
        $private = new OpenSSL\PrivateKey('RS256', $private);
        $public = new RSA\PublicKey('key-1', 'RS256', 'wrong', 'wrong');

        $sslSign = new OpenSSLSign($private);
        $sslVerify = new OpenSSLVerify($public);

        $token = (new Token([], []))->signWith($sslSign);
        $sslVerify->verify($token);
    }

    public function testKeyIsInvalid(): void
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Key is invalid.');

        $public = M::mock(RSA\PublicKey::class);
        $public->shouldReceive('alg')->andReturn('RS256')->once();
        $public->shouldReceive('asPem')->andReturn('invalid pem')->once();

        $sslVerify = new OpenSSLVerify($public);
        $sslVerify->verify(new Token(['alg' => 'RS256'], []));
    }

    public function testAlgorithmNotSupportedBySigner(): void
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Algorithm FOO is not supported');

        $key = \openssl_pkey_new();
        $this->assertNotFalse($key);
        $private = '';
        \openssl_pkey_export($key, $private);
        Assert::string($private);
        $private = new OpenSSL\PrivateKey('FOO', $private);

        $sslVerify = new OpenSSLSign($private);
        $sslVerify->sign(new Token([], []));
    }

    public function testAlgorithmNotSupportedByVerifier(): void
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Algorithm FOO is not supported');

        $public = new RSA\PublicKey('key-1', 'FOO', 'modules', 'exponent');

        $sslVerify = new OpenSSLVerify($public);
        $sslVerify->verify(new Token(['alg' => 'FOO'], []));
    }
}
