<?php

namespace Virtue\JWT\Http\OAuth;

use Psr\SimpleCache\CacheInterface;
use Virtue\JWT\Http\OAuthCachingKeysStore;
use Virtue\JWT\Http\OAuthKeysStore;

class CachingKeyStore implements OAuthCachingKeysStore
{
    private $keysStore;
    private $cache;

    public function __construct(OAuthKeysStore $keysStore, CacheInterface $cache)
    {
        $this->keysStore = $keysStore;
        $this->cache = $cache;
    }

    public function get(string $issuer): array
    {
        if ($this->cache->has($issuer)) {
            return $this->cache->get($issuer);
        }

        $keys = $this->keysStore->get($issuer);
        $this->cache->set($issuer, $keys);

        return $keys;
    }

    public function remove(string $issuer): void
    {
        $this->cache->delete($issuer);
    }
}
