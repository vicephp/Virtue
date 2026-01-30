<?php

namespace Virtue\JWT\Algorithms;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use Mockery as M;
use PHPUnit\Framework\TestCase;
use Virtue\Aws\KmsClient;
use Virtue\Encoding\ASN1;
use Virtue\JWT\SignFailed;

class AwsKmsSignTest extends TestCase
{
    use M\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testUnsupportedAlgorithm(): void
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Algorithm FOO256 is not supported');

        /** @var string $alg */
        $alg = 'FOO256';
        $signer = new AwsKmsSign($alg, M::mock(KmsClient::class));
        $signer->sign('message');
    }

    public function testMessageTooLong(): void
    {
        $this->expectException(SignFailed::class);
        $this->expectExceptionMessage('Message length must be less than 4096 bytes');

        $signer = new AwsKmsSign('RS256', M::mock(KmsClient::class));
        $signer->sign(str_repeat('a', 4097));
    }

    public function testSingMessage(): void
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

    public function testSignMessageES256(): void
    {
        $message = 'message';
        $r = str_repeat("\x01", 32);
        $s = str_repeat("\x02", 32);
        $derSignature = ASN1::seq(ASN1::uint($r), ASN1::uint($s))->encode();

        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) use ($derSignature) {
            $this->assertEquals('ECDSA_SHA_256', $cmd->offsetGet('SigningAlgorithm'));
            return new Result(['Signature' => $derSignature]);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);

        $signer = new AwsKmsSign('ES256', $client);
        $signature = $signer->sign($message);

        $this->assertEquals($r . $s, $signature);
        $this->assertEquals(64, strlen($signature));
    }

    public function testSignMessageES384WithPadding(): void
    {
        $message = 'message';
        // ES384 expects 48 bytes for each component
        $r = str_repeat("\x01", 30); // Needs padding
        $s = str_repeat("\x02", 48);
        $derSignature = ASN1::seq(ASN1::uint($r), ASN1::uint($s))->encode();

        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) use ($derSignature) {
            $this->assertEquals('ECDSA_SHA_384', $cmd->offsetGet('SigningAlgorithm'));
            return new Result(['Signature' => $derSignature]);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);

        $signer = new AwsKmsSign('ES384', $client);
        $signature = $signer->sign($message);

        $expectedR = str_pad($r, 48, "\x00", STR_PAD_LEFT);
        $this->assertEquals($expectedR . $s, $signature);
        $this->assertEquals(96, strlen($signature));
    }

    public function testSignMessageES512(): void
    {
        $message = 'message';
        // ES512 expects 66 bytes for each component
        $r = str_repeat("\x01", 66);
        $s = str_repeat("\x02", 66);
        $derSignature = ASN1::seq(ASN1::uint($r), ASN1::uint($s))->encode();

        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) use ($derSignature) {
            $this->assertEquals('ECDSA_SHA_512', $cmd->offsetGet('SigningAlgorithm'));
            return new Result(['Signature' => $derSignature]);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);

        $signer = new AwsKmsSign('ES512', $client);
        $signature = $signer->sign($message);

        $this->assertEquals($r . $s, $signature);
        $this->assertEquals(132, strlen($signature));
    }

    public function testSignMessageES256WithNegativeIntegers(): void
    {
        $message = 'message';
        // 0x80 is 128, which is negative in 8-bit signed integer
        $r = "\x80" . str_repeat("\x01", 31);
        $s = "\x80" . str_repeat("\x02", 31);
        // ASN1::uint should add a leading \00 if the high bit is set
        $derSignature = ASN1::seq(ASN1::uint($r), ASN1::uint($s))->encode();

        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) use ($derSignature) {
            return new Result(['Signature' => $derSignature]);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);

        $signer = new AwsKmsSign('ES256', $client);
        $signature = $signer->sign($message);

        $this->assertEquals($r . $s, $signature);
        $this->assertEquals(64, strlen($signature));
    }
}
