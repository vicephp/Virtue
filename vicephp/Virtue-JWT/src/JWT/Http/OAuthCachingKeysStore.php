<?php

namespace Virtue\JWT\Http;

interface OAuthCachingKeysStore extends OAuthKeysStore
{
    public function remove(string $issuer): void;
}
