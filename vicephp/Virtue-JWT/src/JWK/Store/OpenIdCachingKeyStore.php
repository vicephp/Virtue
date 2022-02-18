<?php

namespace Virtue\JWK\Store;

use Psr\SimpleCache\CacheInterface;
use Virtue\JWK\KeyCachingStore;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;

class OpenIdCachingKeyStore implements KeyCachingStore
{
    private $keyStore;
    private $cache;

    public function __construct(KeyStore $keyStore, CacheInterface $cache)
    {
        $this->keyStore = $keyStore;
        $this->cache = $cache;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss');
        if ($this->cache->has($issuer)) {
            $keySet = $this->cache->get($issuer);

            return KeySet::fromArray($keySet);
        }

        $keySet = $this->keyStore->getFor($token);
        $this->cache->set($issuer, $keySet);

        return $keySet;
    }

    public function refresh(Token $token): void
    {
        $this->cache->delete($token->payload('iss'));
    }
}
