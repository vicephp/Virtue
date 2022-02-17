<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\HMAC\Key;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class HMACTest extends TestCase
{
    public function testSign()
    {
        $token = Token::ofString('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');
        $hmac = new HMAC(new Key('HS256', 'your-256-bit-secret'));

        $token = $token->signWith($hmac);
        $this->assertEquals('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c', (string) $token);
    }

    public function testVerify()
    {
        $token = Token::ofString('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');
        $hmac = new HMAC(new Key('HS256', 'your-256-bit-secret'));

        $token->verifyWith($hmac);
        $this->assertTrue(true);
    }

    public function testVerificationFailed()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Could not verify signature.');

        $token = Token::ofString('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');
        $hmac = new HMAC(new Key('HS256', 'another-256-bit-secret'));

        $token->verifyWith($hmac);
    }
}
