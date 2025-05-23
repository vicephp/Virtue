<?php

namespace Virtue\JWT\Algorithms;

use Virtue\Aws\KmsClient;
use Virtue\JWT\Algorithm;
use Virtue\JWT\SignFailed;
use Virtue\JWT\SignsToken;
use Webmozart\Assert\Assert;

/**
 * @phpstan-import-type Alg from \Virtue\JWT\Algorithm
 */
class AwsKmsSign extends Algorithm implements SignsToken
{
    private const MaxMessageLengthBytes = 4096;

    /** @var array<Alg,string> */
    private $signingAlgorithms = [
        'RS256' => 'RSASSA_PKCS1_V1_5_SHA_256',
        'RS384' => 'RSASSA_PKCS1_V1_5_SHA_384',
        'RS512' => 'RSASSA_PKCS1_V1_5_SHA_512',
    ];

    /** @var KmsClient */
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

        Assert::string($result['Signature'], 'Issue when signing the message');
        return $result['Signature'];
    }
}
