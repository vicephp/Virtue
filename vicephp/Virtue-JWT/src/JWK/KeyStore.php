<?php

namespace Virtue\JWK;

use Virtue\JWT\Token;

interface KeyStore
{
    public function getFor(Token $token): KeySet;
}
