<?php

namespace Virtue\JWT\Algorithms;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\OpenSSL\PrivateKey;
use Virtue\JWK\Key\RSA\PublicKey;
use Virtue\JWK\KeyCachingStore;
use Virtue\JWK\KeySet;
use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Webmozart\Assert\Assert;

class OpenIdCachingTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /** @var PrivateKey */
    private $private;

    /** @var KeySet */
    private $keySet;

    protected function setUp(): void
    {
        $key = \openssl_pkey_new();
        $this->assertNotFalse($key);
        $private = '';
        \openssl_pkey_export($key, $private);
        Assert::string($private);
        $this->private = new PrivateKey('RS256', $private);

        $details = \openssl_pkey_get_details($key);
        $this->assertNotFalse($details);
        Assert::isMap($details['rsa']);
        Assert::string($details['rsa']['n']);
        Assert::string($details['rsa']['e']);
        $this->keySet = new KeySet([
            new PublicKey(
                'key-1',
                'RS256',
                Base64Url::encode($details['rsa']['n']),
                Base64Url::encode($details['rsa']['e'])
            ),
        ]);
    }

    public function testVerifyUsingKnownKey(): void
    {
        $token = $this->signedToken('key-1');

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token);

        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor($token)->andReturn($this->keySet);
        $keyStore->expects()->refresh(M::any())->never();

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    public function testRefreshesKeySetForUnknownKeyId(): void
    {
        $token = $this->signedToken('key-1');

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token)->twice();

        // the cached key set predates the issuer's key rotation
        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor($token)->twice()->andReturn(new KeySet(), $this->keySet);
        $keyStore->expects()->refresh($token);

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    public function testRejectsKeyIdUnknownAfterRefresh(): void
    {
        $token = $this->signedToken('key-2');

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token)->twice();

        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor($token)->twice()->andReturn($this->keySet);
        $keyStore->expects()->refresh($token);

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionCode(VerificationFailed::ON_KEY);
        $this->expectExceptionMessage('No key found for kid: key-2');

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    public function testDoesNotRefreshForBadSignature(): void
    {
        // the signature of another token, so it does not match this payload
        [$header, $payload] = explode('.', (string) $this->signedToken('key-1', ['sub' => 'someone']));
        [, , $signature] = explode('.', (string) $this->signedToken('key-1'));
        $token = Token::ofString("{$header}.{$payload}.{$signature}");

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token);

        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor($token)->andReturn($this->keySet);
        $keyStore->expects()->refresh(M::any())->never();

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Could not verify signature.');

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    public function testDoesNotRetryWhenKeySetCannotBeLoaded(): void
    {
        $token = $this->signedToken('key-1');

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token);

        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor($token)->andThrow(new \RuntimeException('Connection timed out'));
        $keyStore->expects()->refresh(M::any())->never();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Connection timed out');

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    public function testDoesNotLoadKeySetWhenClaimsFail(): void
    {
        $token = $this->signedToken('key-1');

        $claimsVerifier = M::mock(ClaimsVerify::class);
        $claimsVerifier->expects()->verify($token)->andThrow(new VerificationFailed("Issuer 'evil' is not allowed"));

        $keyStore = M::mock(KeyCachingStore::class);
        $keyStore->expects()->getFor(M::any())->never();
        $keyStore->expects()->refresh(M::any())->never();

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage("Issuer 'evil' is not allowed");

        $token->verifyWith(new OpenIdCaching($keyStore, $claimsVerifier));
    }

    /** @param array<string, mixed> $payload */
    private function signedToken(string $kid, array $payload = []): Token
    {
        return (new Token(['kid' => $kid], $payload))->signWith(new OpenSSLSign($this->private));
    }
}
