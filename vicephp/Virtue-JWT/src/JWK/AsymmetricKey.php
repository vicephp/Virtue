<?php

namespace Virtue\JWK;

/**
 * @phpstan-import-type Alg from \Virtue\JWT\Algorithm
 */
interface AsymmetricKey extends \JsonSerializable
{
    public function asPem(): string;

    /** @return Alg */
    public function alg(): string;

    /**
     * @return array{alg: string, kty: KeyType::*, ...}
     */
    public function jsonSerialize(): array;
}
