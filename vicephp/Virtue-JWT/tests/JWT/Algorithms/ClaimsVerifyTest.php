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
        $this->expectExceptionMessage('Token has expired');
        $token->verifyWith(new ClaimsVerify());
    }

    public function testVerifyNbf()
    {
        $token = new Token([], ['nbf' => time() + 3600]);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Token is not yet valid');
        $token->verifyWith(new ClaimsVerify());
    }

    public function testVerifyIss()
    {
        $token = new Token([], ['iss' => 'https://foo.com']);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Issuer is not allowed');
        $token->verifyWith(new ClaimsVerify(['issuers' => ['https://bar.com']]));
    }

    public function testVerifyAud()
    {
        $token = new Token([], ['aud' => 'https://foo.com']);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Audience is not allowed');
        $token->verifyWith(new ClaimsVerify(['audience' => 'https://bar.com']));
    }

    public function testVerifyAlg()
    {
        $token = new Token([], []);
        $signed = $token->signWith(new HMAC('HS256', 'secret'));
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Algorithm is not allowed');
        $signed->verifyWith(new ClaimsVerify(['algorithms' => ['HS512']]));
    }

    public function testVerifyWrongType()
    {
        $token = new Token(['typ' => 'foo'], []);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Only JWT tokens are allowed');
        $token->verifyWith(new ClaimsVerify());
    }

    public function testVerifyIatBefore()
    {
        $token = new Token([], ['iat' => time()]);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Token was issued after expected time');
        $token->verifyWith(new ClaimsVerify(['iat' => [ 'before' => time() - 3600 ]]));
    }

    public function testVerifyIatAfter()
    {
        $token = new Token([], ['iat' => time()]);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Token was issued before expected time');
        $token->verifyWith(new ClaimsVerify(['iat' => [ 'after' => time() + 3600 ]]));
    }

    public function testVerifySubject()
    {
        $token = new Token([], ['sub' => 'foo']);
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('Subject is not allowed');
        $token->verifyWith(new ClaimsVerify(['subjects' => [ 'bar' ]]));
    }
}
