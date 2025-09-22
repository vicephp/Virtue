<?php

namespace Virtue\JWK;

use Virtue\JWK\Key\OpenSSL;
use Virtue\JWK\Key;

/** @phpstan-import-type Key from KeySet */
interface AsymmetricKey extends \JsonSerializable, OpenSSL\Exportable, Key\WithId
{
    public function alg(): string;

    /** @return Key */
    public function jsonSerialize(): array;
}
