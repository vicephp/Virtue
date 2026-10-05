<?php

namespace Virtue\JWK\Store;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery as M;
use Virtue\Api\TestCase;
use Virtue\JWT\Token;
use GuzzleHttp\Psr7;

class OpenIdKeyStoreTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * @dataProvider invalidOpenIdConfigResponse
     * @param class-string<\Throwable> $expectedException
     */
    public function testInvalidOpenIdConfiguration(
        Response $openIdConfigResponse,
        string $expectedException,
        string $expectedExceptionMessage
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($openIdConfigResponse)
            ->once();

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com/']);

        (new OpenIdKeyStore($client))->getFor($token);
    }

    public function invalidOpenIdConfigResponse(): \Generator
    {
        yield 'not 200 response' => [
            new Response(500),
            \InvalidArgumentException::class,
            'Failed to fetch OpenID configuration',
        ];

        yield '200 response with invalid content' => [
            new Response(200, [], Psr7\Utils::streamFor('asd-fgh')),
            \InvalidArgumentException::class,
            'Invalid OpenID configuration: It must be a valid JSON string',
        ];
    }

    public function testRemoveTrailingSlashFromIssuer(): void
    {
        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com/']);

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']))
        );
        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($response)
            ->once();

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['keys' => [[]]]))
        );
        $client->shouldReceive('request')->andReturn($response)->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }

    public function testGetKeySet(): void
    {
        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']))
        );
        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($response)
            ->once();

        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['keys' => [$key]]))
        );
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/keys', M::any())
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $keySet = $store->getFor($token);
        $this->assertCount(1, $keySet->getKeys());
    }

    public function testInvalidJwksUri(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('The value of jwks_uri must be a valid URI');

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'not a URI']))
        );
        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }

    public function testIssuerMismatch(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('iss claim does not match configured issuer');

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode([
                'iss' => 'http://malicious.com',
                'jwks_uri' => 'http://localhost',
            ]))
        );
        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($response)
            ->once();

        $store = (new OpenIdKeyStore($client))->strict();
        $store->getFor($token);
    }

    public function testEmptyKeys(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('JWKS must have at least one key');

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']))
        );
        $client = M::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn($response)
            ->once();

        $response = new Response(
            200,
            [],
            Psr7\Utils::streamFor(json_encode(''))
        );
        $client->shouldReceive('request')
            ->with('GET', 'https://issuer.ggs-ps.com/keys', M::any())
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }

    public function testRejectsHttpIssuerWithoutRequest(): void
    {
        $client = M::mock(ClientInterface::class);
        $client->expects()->request(M::any(), M::any(), M::any())->never();

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("The value of issuer must be a URL with one of the schemes: https, 'http://issuer.ggs-ps.com' given");

        (new OpenIdKeyStore($client))->getFor(new Token([], ['iss' => 'http://issuer.ggs-ps.com']));
    }

    public function testNeverFetchesHttpJwksUri(): void
    {
        $client = M::mock(ClientInterface::class);
        $client->expects()
            ->request('GET', 'https://issuer.ggs-ps.com/.well-known/openid-configuration', M::any())
            ->andReturn(new Response(200, [], Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'http://issuer.ggs-ps.com/keys']))));
        $client->expects()->request('GET', 'http://issuer.ggs-ps.com/keys', M::any())->never();

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("The value of jwks_uri must be a URL with one of the schemes: https, 'http://issuer.ggs-ps.com/keys' given");

        (new OpenIdKeyStore($client))->getFor(new Token([], ['iss' => 'https://issuer.ggs-ps.com']));
    }

    public function testAllowsConfiguredSchemes(): void
    {
        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $client = M::mock(ClientInterface::class);
        $client->expects()
            ->request('GET', 'http://issuer.local/.well-known/openid-configuration', M::any())
            ->andReturn(new Response(200, [], Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'http://issuer.local/keys']))));
        $client->expects()
            ->request('GET', 'http://issuer.local/keys', M::any())
            ->andReturn(new Response(200, [], Psr7\Utils::streamFor(json_encode(['keys' => [$key]]))));

        $keySet = (new OpenIdKeyStore($client))->allowSchemes('HTTP', 'https')->getFor(new Token([], ['iss' => 'http://issuer.local']));

        $this->assertCount(1, $keySet->getKeys());
    }

    public function testReplacesAllowedSchemes(): void
    {
        $client = M::mock(ClientInterface::class);
        $client->expects()->request(M::any(), M::any(), M::any())->never();

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage("The value of issuer must be a URL with one of the schemes: http, 'https://issuer.ggs-ps.com' given");

        (new OpenIdKeyStore($client))->allowSchemes('http')->getFor(new Token([], ['iss' => 'https://issuer.ggs-ps.com']));
    }

    public function testRequiresAtLeastOneScheme(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new OpenIdKeyStore(M::mock(ClientInterface::class)))->allowSchemes();
    }

    public function testRefusesDiscoveryRedirectToHttp(): void
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('does not use one of the allowed redirect protocols: https');

        $client = $this->client(
            new Response(302, ['Location' => 'http://issuer.ggs-ps.com/.well-known/openid-configuration']),
        );

        (new OpenIdKeyStore($client))->getFor(new Token([], ['iss' => 'https://issuer.ggs-ps.com']));
    }

    public function testRefusesJwksRedirectToHttp(): void
    {
        $this->expectException(BadResponseException::class);
        $this->expectExceptionMessage('does not use one of the allowed redirect protocols: https');

        $client = $this->client(
            new Response(200, [], Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']))),
            new Response(302, ['Location' => 'http://issuer.ggs-ps.com/keys']),
        );

        (new OpenIdKeyStore($client))->getFor(new Token([], ['iss' => 'https://issuer.ggs-ps.com']));
    }

    public function testFollowsRedirectsWithinAllowedSchemes(): void
    {
        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $client = $this->client(
            new Response(301, ['Location' => 'https://login.ggs-ps.com/.well-known/openid-configuration']),
            new Response(200, [], Psr7\Utils::streamFor(json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']))),
            new Response(302, ['Location' => 'https://keys.ggs-ps.com/']),
            new Response(200, [], Psr7\Utils::streamFor(json_encode(['keys' => [$key]]))),
        );

        $keySet = (new OpenIdKeyStore($client))->getFor(new Token([], ['iss' => 'https://issuer.ggs-ps.com']));

        $this->assertCount(1, $keySet->getKeys());
    }

    private function client(Response ...$responses): Client
    {
        return new Client(['handler' => HandlerStack::create(new MockHandler(array_values($responses)))]);
    }
}
