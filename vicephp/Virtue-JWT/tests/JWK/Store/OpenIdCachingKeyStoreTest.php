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

    /** sha1('issuer') */
    private const CACHE_KEY = '9cf32721ebab5d715f51669cbe62b023851870b8';

    private const LOADED = self::CACHE_KEY . '.loaded';

    public function testGetKeySetFromCache(): void
    {
        $keySet = $this->keySet();

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->never();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->get(self::CACHE_KEY)->andReturn($keySet);

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $this->assertSame($keySet, $store->getFor($this->token()));
    }

    public function testGetKeySetFromStore(): void
    {
        $token = $this->token();
        $keySet = $this->keySet();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->get(self::CACHE_KEY)->andReturn(null);
        $cache->expects()->set(self::CACHE_KEY, $keySet);

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor($token)->andReturn($keySet);

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $this->assertSame($keySet, $store->getFor($token));
    }

    public function testReloadsCorruptCacheEntry(): void
    {
        $keySet = $this->keySet();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->get(self::CACHE_KEY)->andReturn([]);
        $cache->expects()->set(self::CACHE_KEY, $keySet);

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->andReturn($keySet);

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $this->assertSame($keySet, $store->getFor($this->token()));
    }

    public function testRefresh(): void
    {
        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->never();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->delete(self::CACHE_KEY);

        $store = new OpenIdCachingKeyStore($keyStore, $cache);
        $store->refresh($this->token());
    }

    public function testRejectsNegativeLoadInterval(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new OpenIdCachingKeyStore(M::mock(KeyStore::class), M::mock(CacheInterface::class), -1);
    }

    public function testThrottledRefreshReplacesKeySetAfterLoading(): void
    {
        $token = $this->token();
        $keySet = $this->keySet();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->has(self::LOADED)->andReturn(false);
        $cache->expects()->set(self::LOADED, true, 60);
        $cache->expects()->set(self::CACHE_KEY, $keySet);
        $cache->expects()->delete(M::any())->never();

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor($token)->andReturn($keySet);

        $store = new OpenIdCachingKeyStore($keyStore, $cache, 60);
        $store->refresh($token);
    }

    public function testThrottledRefreshDoesNotLoadWithinInterval(): void
    {
        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->never();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->has(self::LOADED)->andReturn(true);
        $cache->expects()->set(M::any(), M::any())->never();
        $cache->expects()->delete(M::any())->never();

        $store = new OpenIdCachingKeyStore($keyStore, $cache, 60);
        $store->refresh($this->token());
    }

    public function testFailedThrottledRefreshKeepsCachedKeySet(): void
    {
        $cache = M::mock(CacheInterface::class);
        $cache->expects()->has(self::LOADED)->andReturn(false);
        $cache->expects()->set(self::LOADED, true, 60);
        $cache->expects()->set(self::CACHE_KEY, M::any())->never();
        $cache->expects()->delete(M::any())->never();

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->andThrow(new \RuntimeException('Connection timed out'));

        $store = new OpenIdCachingKeyStore($keyStore, $cache, 60);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Connection timed out');

        $store->refresh($this->token());
    }

    public function testCacheMissWithinIntervalFailsWithoutCallingIssuer(): void
    {
        $cache = M::mock(CacheInterface::class);
        $cache->expects()->get(self::CACHE_KEY)->andReturn(null);
        $cache->expects()->has(self::LOADED)->andReturn(true);

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor(M::any())->never();

        $store = new OpenIdCachingKeyStore($keyStore, $cache, 60);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The key set of issuer issuer could not be loaded within the last 60 seconds');

        $store->getFor($this->token());
    }

    public function testCacheMissAfterIntervalLoadsKeySet(): void
    {
        $token = $this->token();
        $keySet = $this->keySet();

        $cache = M::mock(CacheInterface::class);
        $cache->expects()->get(self::CACHE_KEY)->andReturn(null);
        $cache->expects()->has(self::LOADED)->andReturn(false);
        $cache->expects()->set(self::LOADED, true, 60);
        $cache->expects()->set(self::CACHE_KEY, $keySet);

        $keyStore = M::mock(KeyStore::class);
        $keyStore->expects()->getFor($token)->andReturn($keySet);

        $store = new OpenIdCachingKeyStore($keyStore, $cache, 60);
        $this->assertSame($keySet, $store->getFor($token));
    }

    private function token(): Token
    {
        return new Token([], ['iss' => 'issuer']);
    }

    private function keySet(): KeySet
    {
        return KeySet::fromArray([
            ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'],
        ]);
    }
}
