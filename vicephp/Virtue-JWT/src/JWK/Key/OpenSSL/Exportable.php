<?php

namespace Virtue\JWK\Key\OpenSSL;

interface Exportable extends Encrypted
{
    public function alg(): string;

    public function asPem(): string;
}
