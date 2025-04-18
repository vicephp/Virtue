<?php

namespace Virtue\JWK\Store;

use GuzzleHttp\Client;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;
use Webmozart\Assert\Assert;

class OpenIdKeyStore implements KeyStore
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss');
        Assert::string($issuer, 'Issuer must be a string');
        $response = $this->client->get(rtrim($issuer, '/') . '/.well-known/openid-configuration');
        $config = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        Assert::isArray($config, 'Invalid OpenID configuration');

        if (!filter_var($config['jwks_uri'] ?? '', FILTER_VALIDATE_URL)) {
            throw new \OutOfBoundsException('The value of jwks_uri must be a valid URI');
        }

        $response = $this->client->get($config['jwks_uri']);
        $keySet = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($keySet)) {
            $keySet = [];
        }

        if (empty($keySet['keys'])) {
            throw new \OutOfBoundsException('JWKS must have at least one key');
        }

        return KeySet::fromArray($keySet['keys']);
    }
}
