<?php

namespace Virtue\JWK\Store;

use GuzzleHttp\Client;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;

class OpenIdStore implements KeyStore
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getFor(Token $token): KeySet
    {
        $response = $this->client->get($token->payload('iss') . '/.well-known/openid-configuration');
        $config = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $response = $this->client->get($config['jwks_uri']);
        $keys = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return new KeySet($keys);
    }
}
