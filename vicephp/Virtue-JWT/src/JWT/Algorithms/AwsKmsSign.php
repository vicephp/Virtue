<?php

namespace Virtue\JWT\Algorithms;

use Virtue\Aws\KmsClient;
use Virtue\JWT\Algorithm;
use Virtue\JWT\SignFailed;
use Virtue\JWT\SignsToken;

class AwsKmsSign extends Algorithm implements SignsToken
{
    private const MaxMessageLengthBytes = 4096;

    private $signingAlgorithms = [
        'RS256' => 'RSASSA_PKCS1_V1_5_SHA_256',
        'RS384' => 'RSASSA_PKCS1_V1_5_SHA_384',
        'RS512' => 'RSASSA_PKCS1_V1_5_SHA_512',
    ];

    private $client;

    public function __construct(string $name, KmsClient $client)
    {
        parent::__construct($name);
        $this->client = $client;
    }

    public function sign(string $msg): string
    {
        if (mb_strlen($msg, '8bit') > self::MaxMessageLengthBytes) {
            throw new SignFailed(sprintf('Message length must be less than %d bytes', self::MaxMessageLengthBytes));
        }

        if (empty($this->signingAlgorithms[$this->name])) {
            throw new SignFailed("Algorithm {$this->name} is not supported");
        }

        $result = $this->client->sign([
            'Message'          => $msg,
            'MessageType'      => 'RAW',
            'SigningAlgorithm' => $this->signingAlgorithms[$this->name]
        ]);

        return $result['Signature'];
    }
}
