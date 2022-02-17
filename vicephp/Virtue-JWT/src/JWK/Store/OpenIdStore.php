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

        if (!filter_var($config['jwks_uri'] ?? '', FILTER_VALIDATE_URL)) {
            throw new \OutOfBoundsException('The value of jwks_uri must be a valid URI');
        }

        $response = $this->client->get($config['jwks_uri']);
        $keySet = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (empty($keySet['keys'])) {
            throw new \OutOfBoundsException('JWKS must have at least one key');
        }

        return KeySet::fromArray($keySet['keys']);
    }
}
