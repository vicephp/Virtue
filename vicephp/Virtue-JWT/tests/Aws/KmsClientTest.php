<?php

namespace Virtue\Aws;

use Aws\CommandInterface;
use Aws\MockHandler;
use Aws\Result;
use PHPUnit\Framework\TestCase;

class KmsClientTest extends TestCase
{
    public function testSign1(): void
    {
        $handler = new MockHandler();
        $handler->append(function (CommandInterface $cmd) {
            $this->assertEquals('key/alias', $cmd->offsetGet('KeyId'));

            return new Result(['foo' => 'bar']);
        });

        $client = new KmsClient('key/alias', [
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'handler'     => $handler,
            'credentials' => ['key' => '', 'secret' => '']
        ]);
        $client->sign(['Message' => '<message>', 'SigningAlgorithm' => '<alg>']);
    }
}
