<?php

namespace Virtue\JWT;

interface VerifiesToken
{
    public function verify(string $msg, string $sig): void;
}
