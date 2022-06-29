<?php

namespace Virtue\JWK\Store;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery as M;
use Virtue\Api\TestCase;
use Virtue\JWT\Token;

class OpenIdKeyStoreTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testRemoveTrailingSlashFromIssuer()
    {
        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com/']);

        $response = new Response(200, [], json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']));
        $client = M::mock(Client::class);
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/.well-known/openid-configuration')
            ->andReturn($response)
            ->once();

        $response = new Response(200, [], json_encode(['keys' => [[]]]));
        $client->shouldReceive('get')->andReturn($response)->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }

    public function testGetKeySet()
    {
        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(200, [], json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']));
        $client = M::mock(Client::class);
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/.well-known/openid-configuration')
            ->andReturn($response)
            ->once();

        $key = ['use' => 'sig', 'kty' => 'RSA', 'alg' => 'RS256', 'kid' => 'key id', 'n' => 'modulus', 'e' => 'exponent'];
        $response = new Response(200, [], json_encode(['keys' => [$key]]));
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/keys')
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $keySet = $store->getFor($token);
        $this->assertCount(1, $keySet->getKeys());
    }

    public function testInvalidJwksUri()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('The value of jwks_uri must be a valid URI');

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(200, [], json_encode(['jwks_uri' => 'not a URI']));
        $client = M::mock(Client::class);
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/.well-known/openid-configuration')
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }

    public function testEmptyKeys()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('JWKS must have at least one key');

        $token = new Token([], ['iss' => 'https://issuer.ggs-ps.com']);

        $response = new Response(200, [], json_encode(['jwks_uri' => 'https://issuer.ggs-ps.com/keys']));
        $client = M::mock(Client::class);
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/.well-known/openid-configuration')
            ->andReturn($response)
            ->once();

        $response = new Response(200, [], json_encode(''));
        $client->shouldReceive('get')
            ->with('https://issuer.ggs-ps.com/keys')
            ->andReturn($response)
            ->once();

        $store = new OpenIdKeyStore($client);
        $store->getFor($token);
    }
}
