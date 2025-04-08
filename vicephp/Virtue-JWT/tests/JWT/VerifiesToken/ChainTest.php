<?php

namespace  Virtue\JWT\VerifiesToken;

use PHPUnit\Framework\TestCase;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class ChainTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testAllTheChainWentSuccessfullyThrough(): void
    {
        $chain = new Chain([new AlwaysSucceeds(), new AlwaysSucceeds()]);
        $chain->verify(new Token([], []));
    }

    public function testChainBrokeAfterTheFail(): void
    {
        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('I should break the chain.');

        $chain = new Chain([new AlwaysFails('I should break the chain.'), new AlwaysFails('I should not be thrown.')]);
        $chain->verify(new Token([], []));

        $this->expectException(VerificationFailed::class);
        $this->expectExceptionMessage('I should break the chain.');

        $chain = new Chain([new AlwaysSucceeds(), new AlwaysSucceeds(), new AlwaysFails('I should break the chain.')]);
        $chain->verify(new Token([], []));
    }
}
