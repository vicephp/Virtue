<?php

namespace Virtue\JWK;

interface AsymmetricKey extends \JsonSerializable
{
    public function asPem(): string;

    public function alg(): string;

    /**
     * @return array{alg: string, kty: KeyType::*, ...}
     */
    public function jsonSerialize(): array;
}
