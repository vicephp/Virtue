<?php

namespace Virtue\JWT;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\HMAC\Key;

class TokenTest extends TestCase
{
    public function testParse()
    {
        $token = Token::ofString('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');

        $this->assertEquals('HS256', $token->header('alg'));
        $this->assertEquals('JWT', $token->header('typ'));
        $this->assertEquals('1234567890', $token->payload('sub'));
        $this->assertEquals(1516239022, $token->payload('iat'));
    }

    /**
     * @dataProvider malformedToken
     */
    public function testParseMalformedToken($token)
    {
        $token = Token::ofString($token);

        $this->assertEquals('malformed', $token->header('alg'));
        $this->assertEquals('malformed', $token->header('typ'));
        $this->assertEmpty($token->signature());
    }

    public function malformedToken(): \Generator
    {
        yield 'empty token' => [''];
        yield 'invalid token' => ['invalid toke'];
        yield 'not readable token' => ['a.b.c'];
    }

    public function testSignature()
    {
        $now = time();
        $claims = new ClaimSet();
        $claims->issuer('klaatu.barada.nikto');
        $claims->audience('an-audience');
        $claims->issuedAt($now);
        $claims->expirationTime($now + 300);

        $hmac256 = new Algorithms\HMAC(new Key('HS256', 'your-256-bit-secret'));
        $token = new Token(['kid' => 'pkey_'], $claims->asArray());
        $token = $token->signWith($hmac256);
        $token->verifyWith($hmac256);
        $this->assertNotEmpty($token->signature());
    }

    public function testPayloadIsPreserved()
    {
        $token = Token::ofString('eyJraWQiOiJraWQtMSIsInR5cCI6IkpXVCIsImFsZyI6IkhTMjU2In0.e30.q8fjb85nnNWUoeW4NNXuwWKvFYJ4sjMCA1XJvdOCcsg');
        $this->assertEquals('eyJraWQiOiJraWQtMSIsInR5cCI6IkpXVCIsImFsZyI6IkhTMjU2In0.e30', $token->withoutSig());
    }
}
