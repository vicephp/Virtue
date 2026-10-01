<?php

namespace Virtue\JWK\Store;

use Psr\SimpleCache\CacheInterface;
use Virtue\JWK\KeyCachingStore;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;
use Webmozart\Assert\Assert;

class OpenIdCachingKeyStore implements KeyCachingStore
{
    /** @var KeyStore */
    private $keyStore;
    /** @var CacheInterface */
    private $cache;
    /** @var int */
    private $minLoadInterval;

    /**
     * @param int $minLoadInterval Seconds between two loads of an issuer's key set, failed loads included.
     *  With 0, refresh() drops the cached key set and every cache miss loads it, as in earlier versions.
     *  With more than 0, refresh() replaces the cached key set only after loading a new one succeeded, and an
     *  issuer whose key set cannot be loaded is not called again before the interval has passed.
     */
    public function __construct(KeyStore $keyStore, CacheInterface $cache, int $minLoadInterval = 0)
    {
        Assert::natural($minLoadInterval, 'The minimum load interval must not be negative. Got: %s');
        $this->keyStore = $keyStore;
        $this->cache = $cache;
        $this->minLoadInterval = $minLoadInterval;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $this->issuer($token);
        $key = sha1($issuer);
        $keySet = $this->cache->get($key);
        if ($keySet instanceof KeySet) {
            return $keySet;
        }

        if ($this->loadedRecently($key)) {
            throw new \RuntimeException(sprintf(
                'The key set of issuer %s could not be loaded within the last %d seconds',
                $issuer,
                $this->minLoadInterval
            ));
        }

        return $this->load($token, $key);
    }

    public function refresh(Token $token): void
    {
        $key = sha1($this->issuer($token));
        if ($this->minLoadInterval === 0) {
            $this->cache->delete($key);

            return;
        }

        if (!$this->loadedRecently($key)) {
            $this->load($token, $key);
        }
    }

    private function issuer(Token $token): string
    {
        $issuer = $token->payload('iss', '');
        Assert::string($issuer, 'Issuer must be a string');

        return $issuer;
    }

    private function loadedRecently(string $key): bool
    {
        return $this->minLoadInterval > 0 && $this->cache->has("{$key}.loaded");
    }

    private function load(Token $token, string $key): KeySet
    {
        if ($this->minLoadInterval > 0) {
            // Set before loading, so a failing issuer is not called again within the interval either
            $this->cache->set("{$key}.loaded", true, $this->minLoadInterval);
        }

        $keySet = $this->keyStore->getFor($token);
        $this->cache->set($key, $keySet);

        return $keySet;
    }
}
