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
    /** @var list<string> */
    private $schemes = ['https'];

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getFor(Token $token): KeySet
    {
        $issuer = $token->payload('iss');
        Assert::string($issuer, 'Issuer must be a string');
        $this->assertScheme($issuer, 'issuer');

        $response = $this->client->request(
            'GET',
            rtrim($issuer, '/') . '/.well-known/openid-configuration',
            $this->options()
        );
        Assert::eq($response->getStatusCode(), 200, 'Failed to fetch OpenID configuration');

        $config = json_decode((string)$response->getBody(), true, 512);
        Assert::eq(json_last_error(), JSON_ERROR_NONE, 'Invalid OpenID configuration: It must be a valid JSON string');
        Assert::isArray($config, 'Invalid OpenID configuration: It must be an array');

        if ($this->strict) {
            Assert::keyExists($config, 'iss', 'Invalid OpenID configuration');
            Assert::eq($config['iss'], $issuer, 'iss claim does not match configured issuer');
        }

        if (!filter_var($config['jwks_uri'] ?? '', FILTER_VALIDATE_URL)) {
            throw new \OutOfBoundsException('The value of jwks_uri must be a valid URI');
        }
        Assert::string($config['jwks_uri']);
        $this->assertScheme($config['jwks_uri'], 'jwks_uri');

        $response = $this->client->request('GET', $config['jwks_uri'], $this->options());
        $keySet = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
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

    public function allowSchemes(string ...$schemes): self
    {
        Assert::notEmpty($schemes, 'At least one scheme must be allowed');
        $copy = clone $this;
        $copy->schemes = array_values(array_map('strtolower', $schemes));
        return $copy;
    }

    private function options(): array
    {
        return ['allow_redirects' => ['protocols' => $this->schemes]];
    }

    private function assertScheme(string $url, string $name): void
    {
        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
        if (!in_array($scheme, $this->schemes, true)) {
            throw new \OutOfBoundsException(
                "The value of {$name} must be a URL with one of the schemes: " .
                implode(', ', $this->schemes) . ", '{$url}' given"
            );
        }
    }
}
