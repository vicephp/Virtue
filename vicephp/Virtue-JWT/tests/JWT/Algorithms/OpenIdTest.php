<?php

namespace JWT\Algorithms;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Algorithms\ClaimsVerify;
use Virtue\JWT\Algorithms\OpenId;
use Virtue\JWT\Algorithms\OpenSSLSign;
use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;

class OpenIdTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

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

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->shouldReceive('verify')->once();

        $keyStore = M::mock(KeyStore::class);
        $keyStore->shouldReceive('getFor')->andReturn($keySet)->once();

        $signed = $token->signWith(new OpenSSLSign($private));
        $signed->verifyWith(new OpenId($keyStore, $claimsVerifier));
    }
}
