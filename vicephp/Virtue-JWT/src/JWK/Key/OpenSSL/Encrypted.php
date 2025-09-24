<?php

namespace Virtue\JWK\Key\OpenSSL;

interface Encrypted
{
    public function withPassphrase(string $passphrase): void;

    public function passphrase(): string;
}
