<?php

namespace Virtue\JWK;

interface SymmetricKey
{
    public function alg(): string;

    public function secret(): string;
}
