<?php

namespace Virtue\Aws;

use Aws\Result;

/**
 * @phpstan-type SigningAlgorithm = 'RSASSA_PKCS1_V1_5_SHA_256'|'RSASSA_PKCS1_V1_5_SHA_384'|'RSASSA_PKCS1_V1_5_SHA_512'|'ECDSA_SHA_256'|'ECDSA_SHA_384'|'ECDSA_SHA_512'
 * @phpstan-type KmsClientConfig = array{
 *   version: string,
 *   region: string,
 *   handler: callable,
 *   credentials: array{key: string, secret: string}
 * }
 */
class KmsClient extends \Aws\Kms\KmsClient
{
    /** @var string */
    private $keyAlias;

    /**
     * @param KmsClientConfig $config
     */
    public function __construct(string $keyAlias, array $config)
    {
        parent::__construct($config);
        $this->keyAlias = $keyAlias;
    }


    /**
     * @param array{
     *  Message?: string,
     *  MessageType?: 'RAW'|'DIGEST',
     *  SigningAlgorithm?: SigningAlgorithm
     * } $args
     * @return Result<string,mixed>
     */
    public function sign(array $args = []): Result
    {
        $args['KeyId'] = $this->keyAlias;

        return parent::sign($args);
    }
}
