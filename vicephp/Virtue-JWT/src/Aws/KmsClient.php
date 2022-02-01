<?php

namespace Virtue\Aws;

use Aws\Result;

class KmsClient extends \Aws\Kms\KmsClient
{
    private $keyAlias;

    public function __construct(string $keyAlias, array $config)
    {
        parent::__construct($config);
        $this->keyAlias = $keyAlias;
    }

    public function sign(array $args = []): Result
    {
        $args['KeyId'] = $this->keyAlias;

        return parent::sign($args);
    }
}
