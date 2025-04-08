<?php

namespace Virtue\Aws;

use Aws\Result;

/**
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
     *  MessageType?: string,
     *  SigningAlgorithm?: string
     * } $args
     * @return Result<string,mixed>
     */
    public function sign(array $args = []): Result
    {
        $args['KeyId'] = $this->keyAlias;

        return parent::sign($args);
    }
}
