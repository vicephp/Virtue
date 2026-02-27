<?php

namespace Virtue\JWK\Store;

use GuzzleHttp\ClientInterface;
use Virtue\JWK\KeySet;
use Virtue\JWK\KeyStore;
use Virtue\JWT\Token;
use Webmozart\Assert\Assert;

class OpenIdKeyStore implements KeyStore
{
    /** @var ClientInterface */
    private $client;
    /** @var bool */
    private $strict = false;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss');
        Assert::string($issuer, 'Issuer must be a string');
        $response = $this->client->request('GET', rtrim($issuer, '/') . '/.well-known/openid-configuration');
        $config = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        Assert::isArray($config, 'Invalid OpenID configuration');
        if ($this->strict) {
            Assert::keyExists($config, 'iss', 'Invalid OpenID configuration');
            Assert::eq($config['iss'], $issuer, 'iss claim does not match configured issuer');
        }

        if (!filter_var($config['jwks_uri'] ?? '', FILTER_VALIDATE_URL)) {
            throw new \OutOfBoundsException('The value of jwks_uri must be a valid URI');
        }
        Assert::string($config['jwks_uri']);

        $response = $this->client->request('GET', $config['jwks_uri']);
        $keySet = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($keySet)) {
            $keySet = [];
        }

        if (empty($keySet['keys'])) {
            throw new \OutOfBoundsException('JWKS must have at least one key');
        }
        Assert::isArray($keySet['keys']);
        Assert::allIsMap($keySet['keys']);

        return KeySet::fromArray($keySet['keys']);
    }

    public function strict(): self
    {
        $copy = clone $this;
        $copy->strict = true;
        return $copy;
    }
}
