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

    public function __construct(KeyStore $keyStore, CacheInterface $cache)
    {
        $this->keyStore = $keyStore;
        $this->cache = $cache;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss', '');
        Assert::string($issuer, 'Issuer must be a string');
        $key = sha1($issuer);
        if ($this->cache->has($key)) {
            $keyset = $this->cache->get($key);
            Assert::isInstanceOf($keyset, KeySet::class);
            return $keyset;
        }

        $keySet = $this->keyStore->getFor($token);
        $this->cache->set($key, $keySet);

        return $keySet;
    }

    public function refresh(Token $token): void
    {
        $issuer = $token->payload('iss', '');
        Assert::string($issuer, 'Issuer must be a string');
        $this->cache->delete(sha1($issuer));
    }
}
