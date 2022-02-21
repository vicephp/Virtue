<?php

namespace JWT\Algorithms;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Virtue\JWK\Key\RSA\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWK\Store\OpenIdCachingKeyStore;
use Virtue\JWT\Algorithms\ClaimsVerify;
use Virtue\JWT\Algorithms\OpenIdCaching;
use Virtue\JWT\Algorithms\OpenSSLSign;
use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;

class OpenIdCachingTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testVerifyUsingCachedKeySet()
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
        $cache = M::mock(CacheInterface::class);

        $keyCachingStore = new OpenIdCachingKeyStore($keyStore, $cache);
        $keyStore->shouldNotHaveBeenCalled();
        $cache->shouldReceive('has')->andReturn(true)->once();
        $cache->shouldReceive('get')->andReturn($keySet)->once();

        $signed = $token->signWith(new OpenSSLSign($private));
        $signed->verifyWith(new OpenIdCaching($keyCachingStore, $claimsVerifier));
    }

    public function testVerifyUsingKeySetFromKeyStore()
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
        $cache = M::mock(CacheInterface::class);

        $keyCachingStore = new OpenIdCachingKeyStore($keyStore, $cache);
        $cache->shouldReceive('has')->andReturn(false)->once();
        $keyStore->shouldReceive('getFor')->andReturn($keySet)->once();
        $cache->shouldReceive('set')->once();

        $signed = $token->signWith(new OpenSSLSign($private));
        $signed->verifyWith(new OpenIdCaching($keyCachingStore, $claimsVerifier));
    }

    public function testVerifyRefreshKeyStore()
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
        $claimsVerifier->shouldReceive('verify')->twice();

        $keyStore = M::mock(KeyStore::class);
        $cache = M::mock(CacheInterface::class);

        $keyCachingStore = new OpenIdCachingKeyStore($keyStore, $cache);
        // first try
        $cache->shouldReceive('has')->andReturn(true)->once();
        $cache->shouldReceive('get')->andReturn([])->once();
        // second try, delete cache
        $cache->shouldReceive('delete');
        $cache->shouldReceive('has')->andReturn(false)->once();
        // load fresh key set and put it into the cache
        $keyStore->shouldReceive('getFor')->andReturn($keySet)->once();
        $cache->shouldReceive('set')->once();

        $signed = $token->signWith(new OpenSSLSign($private));
        $signed->verifyWith(new OpenIdCaching($keyCachingStore, $claimsVerifier));
    }
}
