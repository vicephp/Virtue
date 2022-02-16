<?php

namespace Virtue\JWT\Http\OAuth;

use GuzzleHttp\Client;
use Psr\SimpleCache\CacheInterface;
use Virtue\JWT\Http\OAuthKeysStore;

class KeysStore implements OAuthKeysStore
{
    private $client;
    private $cache;

    public function __construct(Client $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function get(string $issuer): array
    {
        $response = $this->client->get($issuer . '/.well-known/openid-configuration');
        $config = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $response = $this->client->get($config['jwks_uri']);
        $keys = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->cache->set($issuer, $keys);

        return $keys;
    }
}
