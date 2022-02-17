<?php

namespace Virtue\JWK\Store;

use Psr\SimpleCache\CacheInterface;
use Virtue\JWK\KeyCachingStore;
use Virtue\JWK\KeySet;
use Virtue\JWT\Token;

class OpenIdCachingStore implements KeyCachingStore
{
    private $keyStore;
    private $cache;

    public function __construct(OpenIdStore $keyStore, CacheInterface $cache)
    {
        $this->keyStore = $keyStore;
        $this->cache = $cache;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss');
        if ($this->cache->has($issuer)) {
            return $this->cache->get($issuer);
        }

        $keys = $this->keyStore->getFor($token);
        $this->cache->set($issuer, $keys);

        return $keys;
    }

    public function refresh(Token $token): void
    {
        $this->cache->delete($token->payload('iss'));
    }
}
