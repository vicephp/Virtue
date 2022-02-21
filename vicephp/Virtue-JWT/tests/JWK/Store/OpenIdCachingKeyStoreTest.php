<?php

namespace Virtue\JWK\Store;

use Mockery as M;
use Psr\SimpleCache\CacheInterface;
use Virtue\Api\TestCase;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;

class OpenIdCachingKeyStoreTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testGetKeySetFromCache()
    {
        $token = new Token([], ['iss' => 'issuer']);

        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];

        $keyStore = M::mock(KeyStore::class);
        $keyStore->shouldNotHaveBeenCalled();

        $cache = M::mock(CacheInterface::class);
        $cache->shouldReceive('has')->andReturn(true)->once();
        $cache->shouldReceive('get')->andReturn([$key])->once();

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $keySet = $store->getFor($token);
        $this->assertCount(1, $keySet->getKeys());
    }

    public function testGetKeySetFromStore()
    {
        $token = new Token([], ['iss' => 'issuer']);

        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $keySet = KeySet::fromArray([$key]);

        $cache = M::mock(CacheInterface::class);
        $cache->shouldReceive('has')->andReturn(false)->once();
        $cache->shouldReceive('set')->with('9cf32721ebab5d715f51669cbe62b023851870b8', $keySet)->once();

        $keyStore = M::mock(KeyStore::class);
        $keyStore->shouldReceive('getFor')->with($token)->andReturn($keySet)->once();

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $keySet = $store->getFor($token);
        $this->assertCount(1, $keySet->getKeys());
    }

    public function testRefresh()
    {
        $token = new Token([], ['iss' => 'issuer']);

        $keyStore = M::mock(KeyStore::class);
        $keyStore->shouldNotHaveBeenCalled();

        $cache = M::mock(CacheInterface::class);
        $cache->shouldReceive('delete')->with('9cf32721ebab5d715f51669cbe62b023851870b8')->once();

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $store->refresh($token);
    }
}
