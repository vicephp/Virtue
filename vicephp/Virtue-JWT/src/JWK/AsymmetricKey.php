<?php

namespace Virtue\JWK;

interface AsymmetricKey extends \JsonSerializable
{
    public function asPem(): string;

    public function alg(): string;

    public function passphrase(): string;

    public function id(): string;
}
