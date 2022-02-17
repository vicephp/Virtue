<?php

namespace Virtue\JWT\Algorithms;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\Aws\KmsClient;
use Virtue\JWT\SignFailed;

class AwsKmsSignTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testUnsupportedAlgorithm()
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Algorithm FOO256 is not supported');

        $signer = new AwsKmsSign('FOO256', M::mock(KmsClient::class));
        $signer->sign('message');
    }

    public function testMessageTooLong()
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Message length must be less than 4096 bytes');

        $signer = new AwsKmsSign('RS256', M::mock(KmsClient::class));
        $signer->sign(str_repeat('a', 4097));
    }

    public function testSingMessage()
    {
        $message = str_repeat('a', 4096);

        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) {
            $this->assertEquals('key/alias', $cmd->offsetGet('KeyId'));

            return new Result(['Signature' => '<signature>']);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);

        $signer = new AwsKmsSign('RS256', $client);
        $signature = $signer->sign($message);

        $this->assertEquals('<signature>', $signature);
    }
}
