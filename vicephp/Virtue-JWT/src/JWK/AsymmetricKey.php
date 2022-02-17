<?php

namespace Virtue\JWK;

interface AsymmetricKey
{
    public function asPem(): string;

    public function alg(): string;
}
