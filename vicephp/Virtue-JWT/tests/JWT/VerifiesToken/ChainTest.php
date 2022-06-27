<?php

namespace  Virtue\JWT\VerifiesToken;

use PHPUnit\Framework\TestCase;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class ChainTest extends TestCase
{
    public function testAllVerifiersMustImplementVerifiesToken()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Chain([new \stdClass()]);
    }

    public function testAllTheChainWentSuccessfullyThrough()
    {
        $chain = new Chain([new AlwaysSucceeds(), new AlwaysSucceeds()]);
        $chain->verify(new Token([],[]));
    }

    public function testChainBrokeAfterTheFail()
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('I should break the chain.');

        $chain = new Chain([new AlwaysFails('I should break the chain.'), new AlwaysFails('I should not be thrown.')]);
        $chain->verify(new Token([],[]));

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('I should break the chain.');

        $chain = new Chain([new AlwaysSucceeds(), new AlwaysSucceeds(), new AlwaysFails('I should break the chain.')]);
        $chain->verify(new Token([],[]));
    }
}
