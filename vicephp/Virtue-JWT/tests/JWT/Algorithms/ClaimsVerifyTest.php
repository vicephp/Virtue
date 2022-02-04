<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWT\ClaimSet;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class ClaimsVerifyTest extends TestCase
{

    public function testVerifyValid()
    {
        $claims = new ClaimSet();
        $now = time();
        $claims->expirationTime($now + 3600);
        $claims->notBefore($now - 3600);
        $claims->issuedAt($now);
        $claims->issuer('https://issuer.com');
        $claims->audience('https://audience.com');

        $token = new Token([], $claims->asArray());
        $signed = $token->signWith(new HMAC('HS256', 'secret'));
        $signed->verifyWith(new ClaimsVerify([
            'audience' => 'https://audience.com',
            'issuers' => ['https://issuer.com'],
            'algorithms' => ['HS256'],
        ]));

        // Pass test if no exception is thrown
        $this->addToAssertionCount(1);
    }

    public function testVerifyExp()
    {
        $token = new Token([], ['exp' => time() - 3600]);
        $this->expectException(VerificationFailed::class);
        $token->verifyWith(new ClaimsVerify());
    }

    public function testVerifyNbf()
    {
        $token = new Token([], ['nbf' => time() + 3600]);
        $this->expectException(VerificationFailed::class);
        $token->verifyWith(new ClaimsVerify());
    }

    public function testVerifyIss()
    {
        $token = new Token([], ['iss' => 'https://foo.com']);
        $this->expectException(VerificationFailed::class);
        $token->verifyWith(new ClaimsVerify(['issuers' => ['https://bar.com']]));
    }

    public function testVerifyAud()
    {
        $token = new Token([], ['aud' => 'https://foo.com']);
        $this->expectException(VerificationFailed::class);
        $token->verifyWith(new ClaimsVerify(['audience' => 'https://bar.com']));
    }

    public function testVerifyAlg()
    {
        $token = new Token([], []);
        $signed = $token->signWith(new HMAC('HS256', 'secret'));
        $this->expectException(VerificationFailed::class);
        $signed->verifyWith(new ClaimsVerify(['algorithms' => ['HS512']]));
    }

}
