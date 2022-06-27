<?php

namespace JWT;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\JWT\SignsToken;
use Virtue\JWT\Token;
use Virtue\JWT\ChainOfVerifiers;
use Virtue\JWT\VerifiesToken;

class ChainofVerifiersTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testAllVerifiersMustImplementVerifiesToken()
    {
        $this->expectException(\InvalidArgumentException::class);

        new ChainOfVerifiers([M::spy(SignsToken::class)]);
    }

    public function testAllTheChainWentSuccessfullyThrough()
    {
        $verifier = M::mock(VerifiesToken::class);
        $verifier->expects('verify')->twice();

        $chain = new ChainOfVerifiers([$verifier, $verifier]);
        $chain->verify(M::spy(Token::class));
    }

    public function testChainBrokeAfterTheFail()
    {
        $this->expectException(\Exception::class);

        $verifier1 = M::mock(VerifiesToken::class);
        $verifier1->expects('verify')->andThrow(\Exception::class);

        $verifier2 = M::mock(VerifiesToken::class);
        $verifier2->shouldNotReceive('verify');

        $chain = new ChainOfVerifiers([$verifier1, $verifier2]);
        $chain->verify(M::spy(Token::class));
    }
}
