<?php

namespace Virtue\JWT\Algorithms;

use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\Aws\KmsClient;

class AwsKmsSignTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testUnsupportedAlgorithm()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Algorithm FOO256 is not supported');

        new AwsKmsSign('FOO256', M::mock(KmsClient::class));
    }

    public function testMessageTooLong()
    {
        $this->expectException(\LengthException::class);
        $this->expectExceptionMessage('Message length must be less than 4096 bytes');

        $signer = new AwsKmsSign('RS256', M::mock(KmsClient::class));
        $signer->sign(str_repeat('a', 4097));
    }

    public function testSingMessage()
    {
        $message = str_repeat('a', 4096);

        $client = M::mock(KmsClient::class);
        $client->shouldReceive('sign')
            ->with([
                'Message'          => $message,
                'MessageType'      => 'RAW',
                'SigningAlgorithm' => 'RSASSA_PKCS1_V1_5_SHA_256'
            ])
            ->andReturn(['Signature' => '<signature>'])
            ->once();

        $signer = new AwsKmsSign('RS256', $client);
        $signature = $signer->sign($message);

        $this->assertEquals('<signature>', $signature);
    }
}
