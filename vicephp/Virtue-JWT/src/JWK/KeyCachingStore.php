<?php

namespace Virtue\JWK;

use Virtue\JWT\Token;

interface KeyCachingStore extends KeyStore
{
    public function refresh(Token $token): void;
}
